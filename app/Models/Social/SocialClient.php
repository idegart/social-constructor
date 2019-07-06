<?php

namespace App\Models\Social;

use Illuminate\Database\Eloquent\Model;

class SocialClient extends Model
{
    protected $guarded = [];

    public function client()
    {
        return $this->morphTo();
    }

    public function socialChannels()
    {
        return $this->belongsToMany(SocialChannel::class, 'social_channel_clients');
    }
}
