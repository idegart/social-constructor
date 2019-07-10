<?php

namespace App\Models\Block;

use App\Models\Block;
use App\Services\PlayService;
use App\Services\Social\SocialChatService;
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
            'next_block' => [
                'nullable',
                Rule::exists((new Block)->getTable(), 'id'),
            ],
        ];
    }

    public function nextBlock()
    {
        return $this->belongsTo(Block::class, 'next_block');
    }

    public function playBlock(PlayService $playService)
    {
        $playService->sendMessage($this->message);

        if (!$this->next_block) {
            return null;
        }

        return $this->nextBlock;
    }
}
