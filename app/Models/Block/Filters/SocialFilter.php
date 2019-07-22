<?php

namespace App\Models\Block\Filters;

use App\Models\Block;
use App\Models\Block\BaseBlock;
use App\Models\Social\Socialable\BaseMessage;
use App\Services\PlayService;
use Illuminate\Validation\Rule;

class SocialFilter extends BaseBlock
{
    protected $guarded = [];

    protected $table = 'social_filter_blocks';

    public function validationRules(): array
    {
        return [
            'vkontakte_next_block_id' => [
                'nullable',
                Rule::exists((new Block)->getTable(), 'id'),
            ],
            'telegram_next_block_id' => [
                'nullable',
                Rule::exists((new Block)->getTable(), 'id'),
            ],
            'other_next_block_id' => [
                'nullable',
                Rule::exists((new Block)->getTable(), 'id'),
            ],
        ];
    }

    public function playBlock(PlayService $playService) : ?Block
    {
        /** @var BaseMessage $message */
        $message = $playService->socialMessage->message;

        switch ($message->getSocial()) {
            case PlayService::VKONTAKTE:
                return $this->vkontakteNextBlock;
            case PlayService::TELEGRAM:
                return $this->telegramNextBlock;
            default:
                return $this->otherNextBlock;
        }
    }

    public function vkontakteNextBlock()
    {
        return $this->belongsTo(Block::class, 'vkontakte_next_block_id');
    }

    public function telegramNextBlock()
    {
        return $this->belongsTo(Block::class, 'telegram_next_block_id');
    }

    public function otherNextBlock()
    {
        return $this->belongsTo(Block::class, 'other_next_block_id');
    }
}
