<?php

namespace App\Models\Social;

use Illuminate\Database\Eloquent\Model;

class SocialClient extends Model
{
    public function client()
    {
        return $this->morphTo();
    }

    public function channel()
    {
        return $this->belongsTo(SocialChannel::class);
    }

    public function chats()
    {
        return $this->hasMany(SocialChat::class);
    }
}
