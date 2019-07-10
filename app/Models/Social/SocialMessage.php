<?php

namespace App\Models\Social;

use Illuminate\Database\Eloquent\Model;

class SocialMessage extends Model
{
    protected $guarded = [];

    protected $with = [
        'message',
    ];

    public function message()
    {
        return $this->morphTo();
    }

    public function socialChat()
    {
        return $this->belongsToMany(SocialChat::class, 'social_chat_messages');
    }
}
