<?php

namespace App\Models\Social\Socialable\Telegram;

use App\Models\Social\Socialable\BaseMessage;
use App\Services\PlayService;

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

    public function getSocial(): string
    {
        return PlayService::TELEGRAM;
    }
}
