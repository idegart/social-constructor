<?php

namespace App\Models\Social;

use App\Models\Script;
use Illuminate\Database\Eloquent\Model;

class SocialChannel extends Model
{
    protected $guarded = [];

    protected $with = [
        'channel',
    ];

    public function channel()
    {
        return $this->morphTo();
    }

    public function socialClients()
    {
        return $this->belongsToMany(SocialClient::class, 'social_channel_clients');
    }

    public function socialChats()
    {
        return $this->hasMany(SocialChat::class);
    }

    public function scripts()
    {
        return $this->belongsToMany(Script::class, 'social_channel_scripts');
    }

    public function scriptsCacheKey()
    {
        return "scripts_cache_key_" . $this->getKey();
    }
}
