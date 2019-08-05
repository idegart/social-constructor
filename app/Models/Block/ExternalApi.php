<?php

namespace App\Models\Block;

use App\Models\Block;
use App\Models\Block\ExternalApi\ExternalApiOption;
use App\Models\Script\ScriptExternalApi;
use App\Models\Script\ScriptVariable;
use App\Models\Social\Socialable\BaseChannel;
use App\Models\Social\Socialable\BaseClient;
use App\Models\Social\Socialable\BaseMessage;
use App\Models\Social\SocialChatVariable;
use App\Services\PlayService;
use App\Services\Social\SocialKeyboard;
use App\Services\Social\SocialKeyboardButton;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Psr\Http\Message\ResponseInterface;
use Validator;

class ExternalApi extends BaseBlock
{
    protected $table = 'external_api_blocks';

    protected $guarded = [];

    protected $with = [
        'options'
    ];

    private $freshCount = 0;

    public function validationRules(): array
    {
        return [
            'external_api_id' => [
                'sometimes', 'nullable',
                Rule::exists((new ScriptExternalApi())->getTable(),'id'),
            ],
            'handler' => [
                'sometimes', 'nullable', 'string', 'alpha_dash',
            ],
            'option_store' => [
                'sometimes', 'required', 'string', 'alpha_dash',
            ],
            'option_remove' => [
                'sometimes',
                Rule::exists((new ExternalApiOption())->getTable(), 'id')
            ],
            'option_update' => [
                'sometimes', 'array',
            ],
            'option_update.id' => [
                'required_with:option_update',
                Rule::exists((new ExternalApiOption())->getTable(), 'id')
            ],
            'option_update.next_block_id' => [
                'required_with:option_update',
                Rule::exists((new Block)->getTable(), 'id'),
            ],
        ];
    }

    public function playBlock(PlayService $playService): ?Block
    {
        return $this->toPlay($playService, true);
    }

    public function playContinue(PlayService $playService) : ?Block
    {
        return $this->toPlay($playService);
    }

    private function toPlay(PlayService $playService, $isInitial = false) : ?Block
    {
        if (!$this->externalApi) {
            return null;
        }

        try {
            $response = $this->sendRequest($playService, $isInitial);
        } catch (Exception $exception) {
            $playService->setCurrentStep(null);
            $playService->sendMessage('Произошла ошибка. Обратитесь в службу поддержки! (' . $exception->getMessage() . ')');
            return null;
        }

        $validator = $this->validateResponse($response);

        if ($validator->fails()) {
            $playService->sendMessage('Произошла ошибка. Обратитесь в службу поддержки! (Неверный формат ответа)');
            return null;
        }

        $validResponse = $validator->valid();

        if (key_exists('message', $validResponse)) {
            $keyboard = null;

            if (key_exists('keyboard', $validResponse)) {
                $keyboard = new SocialKeyboard();

                foreach ($validResponse['keyboard'] as $keyboardButton) {
                    $button = new SocialKeyboardButton($keyboardButton);
                    $keyboard->addButton($button);
                }
            }

            $playService->sendMessage($validResponse['message'], $keyboard);
        }

        if (key_exists('wait', $validResponse) && $validResponse['wait']) {
            $playService->setCurrentStep($this->block);
        }

        if (key_exists('variable_update', $validResponse)) {
            $variablesToUpdate = $validResponse['variable_update'];

            $this->updateVariables($playService, $variablesToUpdate);
        }

        if (key_exists('fresh', $validResponse)) {
            if ($this->freshCount > 5) {
                $playService->sendMessage('Превышен лемит перегрузок!');
                $playService->setCurrentStep(null);
                return null;
            }
            $this->freshCount++;
            $this->toPlay($playService, $isInitial);
        }

        if (key_exists('next_option', $validResponse)) {
            /** @var ExternalApiOption $option */
            $option = $this->options()->where('key', '=', $validResponse['next_option'])->first();

            return $option ? $option->nextBlock : null;
        }

        return null;
    }

    private function sendRequest(PlayService $playService, $isInitial = false) : ResponseInterface
    {
        /** @var BaseChannel $channel */
        $channel = $playService->socialChannel->channel;

        /** @var BaseClient $client */
        $client = $playService->socialClient->client;

        /** @var BaseMessage $message */
        $message = $playService->socialMessage->message;

        $data = collect([
            'social' => $message->getSocialType(),
            'is_initial' => $isInitial,
            'script' => [
                'id' => $this->block->schema->script->id,
                'title' => $this->block->schema->script->title,
            ],
            'schema' => [
                'id' => $this->block->schema->id,
                'title' => $this->block->schema->title,
            ],
            'channel' => [
                'social' => $channel->getSocialType(),
                'real_id' => $channel->getRealId(),
                'name' => $channel->getChannelName(),
            ],
            'user' => [
                'social' => $client->getSocialType(),
                'real_id' => $client->getRealId(),
                'name' => $client->getName(),
            ],
            'message' => [
                'social' => $message->getSocialType(),
                'real_id' => $message->getRealId(),
                'text' => $message->getText(),
            ],
            'variables' => $playService->socialChat->variables->map(function (SocialChatVariable $variable) {
                return [
                    'variable' => $variable->scriptVariable->variable,
                    'type' => $variable->scriptVariable->type,
                    'value' => $variable->value ?? ''
                ];
            })->toArray(),
        ]);

        if ($this->handler) {
            $data->put('handler', $this->handler);
        }

        $apiClient = new Client();
        $externalApi = $this->externalApi;

        $postData = collect([
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'form_params' => $data->toArray(),
        ]);

        if ($externalApi->auth_login) {
            $postData->put('auth', [
                $externalApi->auth_login, $externalApi->auth_password
            ]);
        }

        return $apiClient->post($externalApi->url, $postData->toArray());
    }

    private function validateResponse(ResponseInterface $response) : \Illuminate\Validation\Validator
    {
        $responseData = json_decode($response->getBody()->getContents(), true);

        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($responseData, [
            'next_option' => [
                'sometimes', 'required',
                Rule::exists((new ExternalApiOption)->getTable(), 'key'),
            ],
            'wait' => [
                'required_without:next_option', 'boolean',
            ],
            'fresh' => [
                'sometimes', 'boolean',
            ],
            'message' => [
                'sometimes', 'string',
            ],
            'variable_update' => [
                'sometimes', 'array',
            ],
            'variable_update.*.variable' => [
                'required', 'string',
                Rule::exists((new ScriptVariable)->getTable(), 'variable'),
            ],
            'variable_update.*.value' => [
                'nullable',
            ],
        ]);

        return $validator;
    }

    public function updateVariables(PlayService $playService, array $variablesToUpdate)
    {
        $scriptVariables = $this->block->schema->script->variables;
        $chatVariables = $playService->socialChat->variables;

        foreach ($variablesToUpdate as $variableToUpdate) {
            $scriptVariable = $scriptVariables->firstWhere('variable','=', $variableToUpdate['variable']);

            if (!$scriptVariable) {
                continue;
            }

            /** @var SocialChatVariable $chatVariable */
            $chatVariable = $chatVariables->firstWhere('script_variable_id', $scriptVariable->getKey());

            if (!$chatVariable) {
                continue;
            }

            $chatVariable->updateValue($variableToUpdate['value']);
        }
    }

    public function options()
    {
        return $this->hasMany(ExternalApiOption::class, 'external_api_blocks_id')->orderBy('id');
    }

    public function setHandlerAttribute($value)
    {
        $this->attributes['handler'] = Str::upper($value);
    }

    public function setOptionStoreAttribute($value)
    {
        $this->options()->create(['key' => $value]);
    }

    public function setOptionRemoveAttribute($value)
    {
        $this->options()->where('id', '=', $value)->delete();
    }

    public function setOptionUpdateAttribute($value)
    {
        $optionId = $value['id'];
        $nextBlockId = $value['next_block_id'];

        $this->options()->where('id', '=', $optionId)->update([
            'next_block_id' => $nextBlockId
        ]);
    }

    public function externalApi()
    {
        return $this->belongsTo(ScriptExternalApi::class, 'external_api_id');
    }
}
