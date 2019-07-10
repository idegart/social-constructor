<?php

namespace App\Models\Social;

use Illuminate\Database\Eloquent\Model;

class SocialMessage extends Model
{
    protected $guarded = [];

    public function message()
    {
        return $this->morphTo();
    }

    public function socialClient()
    {
        return $this->belongsTo(SocialClient::class);
    }

    public function socialChat()
    {
        return $this->belongsTo(SocialChat::class);
    }
}
