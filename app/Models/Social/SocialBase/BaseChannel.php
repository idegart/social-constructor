<?php

namespace App\Models\Social\SocialBase;

use App\Models\Social\Interfaces\SocialChannel\{SocialChannelAttributesInterface, SocialChannelRelationsInterface};
use App\Models\Social\SocialChannel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

abstract class BaseChannel extends Model implements SocialChannelRelationsInterface, SocialChannelAttributesInterface
{
    public function socialChannel() : MorphOne
    {
        return $this->morphOne(SocialChannel::class, 'channel');
    }
}
