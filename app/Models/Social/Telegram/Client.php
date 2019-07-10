<?php

namespace App\Models\Social\Telegram;

use App\Models\Social\SocialBase\BaseClient;

class Client extends BaseClient
{
    protected $table = 'telegram_clients';

    protected $guarded = [];
}
