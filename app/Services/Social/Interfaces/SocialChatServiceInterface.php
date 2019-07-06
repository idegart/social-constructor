<?php

namespace App\Services\Social\Interfaces;

use App\Models\Social\SocialBase\BaseClient;
use App\Models\Social\SocialBase\BaseMessage;
use Illuminate\Support\Collection;

interface SocialChatServiceInterface
{
    public function storeMessage(BaseClient $client, Collection $messageData) : BaseMessage ;
}
