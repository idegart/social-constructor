<?php

namespace App\Models\Social\SocialBase;

use App\Models\Social\Interfaces\SocialMessage\SocialMessageAttributesInterface;
use App\Models\Social\Interfaces\SocialMessage\SocialMessageRelationsInterface;
use App\Models\Social\SocialMessage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

abstract class BaseMessage extends Model implements SocialMessageRelationsInterface, SocialMessageAttributesInterface
{
    public function socialMessage(): MorphOne
    {
        return $this->morphOne(SocialMessage::class, 'message');
    }

}
