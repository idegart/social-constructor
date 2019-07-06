<?php

namespace App\Models\Social;

use Illuminate\Database\Eloquent\Model;

class SocialChat extends Model
{
    protected $guarded = [];

    public function socialMessages()
    {
        return $this->hasMany(SocialMessage::class);
    }

    public function socialClient()
    {
        return $this->hasMany(SocialClient::class);
    }
}
