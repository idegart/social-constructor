<?php

namespace App\Models\Social;

use App\Models\Block;
use App\Services\PlayService;
use Illuminate\Database\Eloquent\Model;

class SocialChat extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::created(function (SocialChat $socialChat) {

            dump('pre-created');

            parent::created($socialChat);

            dump('post-created');

            PlayService::setChatInitialVariables($socialChat);

            dump('last-created');
        });
    }

    protected $guarded = [];

    public function socialClient()
    {
        return $this->belongsTo(SocialClient::class);
    }

    public function socialChannel()
    {
        return $this->belongsTo(SocialChannel::class);
    }

    public function socialMessages()
    {
        return $this->belongsToMany(SocialMessage::class, 'social_chat_messages');
    }

    public function currentBlock()
    {
        return $this->belongsTo(Block::class, 'current_block_id');
    }

    public function variables()
    {
        return $this->hasMany(SocialChatVariable::class);
    }
}
