<?php

namespace App\Models\Social\Socialable;

use App\Models\Social\SocialChannel;
use Auth;
use Illuminate\Database\Eloquent\Model;

abstract class BaseChannel extends Model
{
    protected $guarded = [];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::created(function (BaseChannel $channel) {
            $channel->socialChannel()->create([
                'user_id' => Auth::id(),
            ]);
        });
    }

    public function socialChannel()
    {
        return $this->morphOne(SocialChannel::class, 'channel');
    }


    abstract public function getChannelPhoto() : string ;
    abstract public function getChannelName() : string ;
    abstract public function getChannelLink() : string ;
    abstract public function getCallbackUrl() : string ;
    abstract public function getAccessLink() : string ;
    abstract public function hasAccessToken() : bool ;
    abstract public function setChannelInfo() : bool ;
}
