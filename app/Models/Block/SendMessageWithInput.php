<?php


namespace App\Models\Block;


use App\Models\Block;
use App\Models\Script\ScriptVariable;
use App\Models\Social\Socialable\BaseMessage;
use App\Models\Social\SocialChatVariable;
use App\Services\PlayService;
use Illuminate\Validation\Rule;

class SendMessageWithInput extends BaseBlock
{
    protected $table = 'send_message_with_input_blocks';

    protected $guarded = [];

    public function validationRules(): array
    {
        return [
            'message' => [
                'nullable', 'string',
            ],
            'param_id' => [
                'nullable',
                Rule::exists((new ScriptVariable)->getTable(), 'id'),
            ],
            'next_block_id' => [
                'nullable',
                Rule::exists((new Block)->getTable(), 'id'),
            ],
            'error_next_block_id' => [
                'sometimes', 'nullable',
                Rule::in((new Block)->getTable(), 'id'),
            ],
        ];
    }

    public function playBlock(PlayService $playService) : ?Block
    {
        $message = $playService->messageReplaceWithVariables($this->block->schema->script, $this->message);

        $playService->sendMessage($message);

        $playService->setCurrentStep($this->block);

        return false;
    }

    public function playContinue(PlayService $playService)
    {
        /** @var BaseMessage $message */
        $message = $playService->socialMessage->message;

        /** @var SocialChatVariable $playVariable */
        $playVariable = $playService->socialChat->variables()->where('script_variable_id', '=', $this->variable->id)->first();

        $updated = $playVariable->updateValue($message->getText());

        if ($updated !== true) {
            if (is_string($updated)) {
                $playService->sendMessage($updated);
            }

            return $this->errorNextBlock;
        }

        return $this->nextBlock;
    }

    public function errorNextBlock()
    {
        return $this->belongsTo(Block::class, 'error_next_block_id');
    }

    public function nextBlock()
    {
        return $this->belongsTo(Block::class, 'next_block_id');
    }

    public function variable()
    {
        return $this->belongsTo(ScriptVariable::class, 'param_id');
    }
}
