<?php

namespace App\Models\Social\Telegram;

use App\Models\Social\SocialBase\BaseMessage;

class Message extends BaseMessage
{
    protected $table = 'telegram_messages';

    protected $guarded = [];

    public function getText()
    {
        return $this->text;
    }

}
