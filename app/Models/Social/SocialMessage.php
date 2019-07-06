<?php

namespace App\Models\Social;

use Illuminate\Database\Eloquent\Model;

class SocialMessage extends Model
{
    public function message()
    {
        return $this->morphTo();
    }

    public function chat()
    {
        return $this->belongsTo(SocialChat::class);
    }

    public function client()
    {
        return $this->belongsTo(SocialClient::class);
    }
}
