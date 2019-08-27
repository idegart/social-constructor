<?php

namespace App\Models\Block;

use App\Models\Block;
use App\Models\Block\DialogFlow\DialogFlowAction;
use App\Models\Social\Socialable\BaseMessage;
use App\Services\PlayService;
use DialogFlow\Client;
use DialogFlow\Method\QueryApi;
use DialogFlow\Model\Query;
use Illuminate\Validation\Rule;

class DialogFlow extends BaseBlock
{
    protected $table = 'dialogflow_blocks';

    protected $guarded = [];

    protected $with = [
        'actions'
    ];

    public function validationRules(): array
    {
        return [
            'is_initial' => [
                'sometimes', 'boolean',
            ],
            'access_token' => [
                'sometimes', 'string',
            ],
            'action_store' => [
                'sometimes', 'required', 'string',
            ],
            'action_update' => [
                'sometimes', 'array',
            ],
            'action_update.id' => [
                'required_with:button_update',
                Rule::exists((new DialogFlowAction)->getTable(), 'id')
            ],
            'action_update.next_block_id' => [
                'required_with:button_update',
                Rule::exists((new Block)->getTable(), 'id'),
            ],
            'action_remove' => [
                'sometimes',
                Rule::exists((new DialogFlowAction)->getTable(), 'id')
            ]
        ];
    }

    public function playBlock(PlayService $playService)
    {
        return $this->initDialog($playService);
    }

    public function playContinue(PlayService $playService)
    {
        return $this->initDialog($playService);
    }

    private function initDialog(PlayService $playService)
    {
        /** @var BaseMessage $message */
        $message = $playService->socialMessage->message;

        $client = new Client($this->access_token);

        $queryApi = new QueryApi($client);

        $meaning = $queryApi->extractMeaning($message->getText(), [
            'session_id' => $playService->socialClient->id,
        ]);

        $response = new Query($meaning);

        foreach ($this->actions as $flowAction) {
            if ($response->getResult()->getAction() === $flowAction->action) {

                if ($messageOut = $response->getResult()->getFulfillment()->getSpeech()) {
                    $playService->sendMessage($messageOut);
                }

                if ($response->getResult()->getActionIncomplete()) {
                    $playService->setCurrentStep($this->block);

                    return false;
                }

                return $flowAction->nextBlock;
            }
        }

        return null;
    }

    public function actions()
    {
        return $this->hasMany(DialogFlowAction::class, 'dialogflow_block_id');
    }

    public function setActionStoreAttribute($value)
    {
        $this->actions()->create([
            'action' => $value
        ]);
    }

    public function setActionUpdateAttribute($value)
    {
        $buttonId = $value['id'];
        $nextBlockId = $value['next_block_id'];

        $this->actions()->where('id', '=', $buttonId)->update([
            'next_block_id' => $nextBlockId
        ]);
    }

    public function setActionRemoveAttribute($buttonId)
    {
        $this->actions()->where('id', '=', $buttonId)->delete();
    }
}
