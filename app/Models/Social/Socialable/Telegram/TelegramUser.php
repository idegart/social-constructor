<?php

namespace App\Models\Social\Socialable\Telegram;

use App\Models\Social\Socialable\BaseClient;
use App\Services\PlayService;

class TelegramUser extends BaseClient
{
    public function getRealId(): string
    {
        return $this->id;
    }

    public function getSocialType(): string
    {
        return PlayService::TELEGRAM;
    }

    public function getName(): string
    {
        return $this->first_name;
    }

}
