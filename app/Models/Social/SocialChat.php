<?php

namespace App\Models\Social;

use App\Models\Block;
use Illuminate\Database\Eloquent\Model;

class SocialChat extends Model
{
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
}
