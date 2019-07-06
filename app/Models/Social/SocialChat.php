<?php

namespace App\Models\Social;

use Illuminate\Database\Eloquent\Model;

class SocialChat extends Model
{
    public function chat()
    {
        return $this->morphTo();
    }

    public function channel()
    {
        return $this->belongsTo(SocialChannel::class);
    }

    public function client()
    {
        return $this->belongsTo(SocialClient::class);
    }

    public function messages()
    {
        return $this->hasMany(SocialMessage::class);
    }
}
