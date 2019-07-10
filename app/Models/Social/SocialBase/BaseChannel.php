<?php

namespace App\Models\Social\SocialBase;

use App\Models\Social\Interfaces\SocialChannel\{SocialChannelAttributesInterface, SocialChannelRelationsInterface};
use App\Models\Social\SocialChannel;
use App\Models\Social\SocialChat;
use App\Models\Social\SocialClient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Collection;

abstract class BaseChannel extends Model implements SocialChannelRelationsInterface, SocialChannelAttributesInterface
{
    public function socialChannel() : MorphOne
    {
        return $this->morphOne(SocialChannel::class, 'channel');
    }

    abstract public function sendMessage(SocialClient $socialClient, SocialChat $socialChat, string $message, Collection $keyboard);
}
