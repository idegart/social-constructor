<?php

namespace App\Models\Social\Socialable\Telegram;

use App\Models\Social\Socialable\BaseMessage;

class TelegramMessage extends BaseMessage
{
    public function getText()
    {
        return $this->text;
    }

    public function isOut(): bool
    {
        return false;
    }

}
