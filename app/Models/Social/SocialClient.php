<?php

namespace App\Models\Social;

use Illuminate\Database\Eloquent\Model;

class SocialClient extends Model
{
    protected $guarded = [];

    protected $with = [
        'client',
    ];

    public function client()
    {
        return $this->morphTo();
    }

    public function socialChannels()
    {
        return $this->belongsToMany(SocialChannel::class, 'social_channel_clients');
    }

    public function socialChats()
    {
        return $this->hasMany(SocialChat::class);
    }
}
