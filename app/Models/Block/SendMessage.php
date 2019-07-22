<?php

namespace App\Models\Block;

use App\Models\Block;
use App\Services\PlayService;
use Illuminate\Validation\Rule;

class SendMessage extends BaseBlock
{
    protected $guarded = [];

    protected $table = 'send_message_blocks';

    public function validationRules(): array
    {
        return [
            'message' => [
                'nullable', 'string',
            ],
            'next_block_id' => [
                'nullable',
                Rule::exists((new Block)->getTable(), 'id'),
            ],
        ];
    }

    public function nextBlock()
    {
        return $this->belongsTo(Block::class, 'next_block_id');
    }

    public function playBlock(PlayService $playService) : ?Block
    {
        $message = $playService->messageReplaceWithVariables($this->block->schema->script, $this->message);

        $playService->sendMessage($message);

        return $this->nextBlock;
    }
}
