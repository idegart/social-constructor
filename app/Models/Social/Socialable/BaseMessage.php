<?php

namespace App\Models\Social\Socialable;

use App\Models\Social\SocialMessage;
use Illuminate\Database\Eloquent\Model;

abstract class BaseMessage extends Model
{
    protected $guarded = [];

    public $incrementing = false;

    protected $appends = [
        'socialType',
    ];

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

    abstract public function getSocialType() : string ;
    abstract public function getRealId() : string ;
    abstract public function getText();
    abstract public function isOut() : bool ;
}
