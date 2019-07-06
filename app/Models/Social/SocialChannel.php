<?php

namespace App\Models\Social;

use App\Models\Script;
use Illuminate\Database\Eloquent\Model;

class SocialChannel extends Model
{
    protected $guarded = [];

    protected $with = [
        'channel'
    ];

    public function channel()
    {
        return $this->morphTo();
    }

    public function clients()
    {
        return $this->hasMany(SocialClient::class);
    }

    public function chats()
    {
        return $this->hasMany(SocialChat::class);
    }

    public function scripts()
    {
        return $this->belongsToMany(Script::class, 'social_channel_scripts');
    }
}
