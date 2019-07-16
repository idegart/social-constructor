<?php

namespace App\Models\Block\SendMessageWithKeyboard;

use App\Models\Block;
use Illuminate\Database\Eloquent\Model;

class MessageKeyboardButton extends Model
{
    protected $table = 'send_message_with_keyboard_block_buttons';

    protected $guarded = [];

    public function nextBlock()
    {
        return $this->belongsTo(Block::class, 'next_block_id');
    }

}
