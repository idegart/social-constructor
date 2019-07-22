<?php

namespace App\Models\Social\Socialable;

use App\Models\Social\SocialMessage;
use Illuminate\Database\Eloquent\Model;

abstract class BaseMessage extends Model
{
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::created(function (BaseMessage $message) {
            $message->socialMessage()->create([
                'is_out' => $message->isOut()
            ]);
        });
    }

    public function socialMessage()
    {
        return $this->morphOne(SocialMessage::class, 'message');
    }

    abstract public function getText();

    abstract public function isOut() : bool ;

    abstract public function getSocial() : string ;
}
