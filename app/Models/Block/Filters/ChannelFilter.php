<?php

namespace App\Models\Block\Filters;

use App\Models\Block;
use App\Models\Block\BaseBlock;
use App\Models\Social\Socialable\BaseChannel;
use App\Models\Social\Socialable\BaseMessage;
use App\Services\PlayService;
use Illuminate\Validation\Rule;

class ChannelFilter extends BaseBlock
{
    protected $guarded = [];

    protected $table = 'channel_filter_blocks';

    public function validationRules(): array
    {
        return [
            'channels' => [
                'nullable', 'string',
            ],
            'channels_next_block_id' => [
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
        /** @var BaseChannel $channel */
        $channel = $playService->socialChannel->channel;

        $channelId = null;

        if ($channel) {
            $channelId = $channel->getRealId();
        }

        if (!$channelId) {
            /** @var BaseMessage $message */
            $message = $playService->socialMessage->message;

            $channelId = $message->channel_id ?? null;
        }

        if (!$channelId) {
            return $this->otherNextBlock;
        }

        $channels = explode('|', $this->channels);

        return in_array($channelId, $channels)
            ? $this->channelsNextBlock
            : $this->otherNextBlock;
    }

    public function channelsNextBlock()
    {
        return $this->belongsTo(Block::class, 'channels_next_block_id');
    }

    public function otherNextBlock()
    {
        return $this->belongsTo(Block::class, 'other_next_block_id');
    }
}
