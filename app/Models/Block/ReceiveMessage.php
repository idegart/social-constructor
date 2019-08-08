<?php

namespace App\Models\Block;

use App\Models\Block;
use App\Models\Social\Socialable\BaseMessage;
use App\Services\PlayService;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ReceiveMessage extends BaseBlock
{
    protected $guarded = [];

    protected $table = 'receive_message_blocks';

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
        /** @var BaseMessage $message */
        $message = $playService->socialMessage->message;

        $availableMessages = explode('|', $this->message);

        foreach ($availableMessages as $availableMessage) {
            if (Str::startsWith($availableMessage, '[') && Str::endsWith($availableMessage, ']')) {

                $needle = rtrim(ltrim($availableMessage, "["), "]");

                if (Str::contains($message->getText(), $needle)) {
                    return $this->nextBlock;
                }
            }

            if (mb_strtolower($availableMessage) === mb_strtolower($message->getText())) {
                return $this->nextBlock;
            }
        }

        return null;
    }
}
