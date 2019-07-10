<?php

namespace App\Services\Social\Interfaces;

use App\Models\Social\SocialChat;
use App\Models\Social\SocialClient;
use App\Models\Social\SocialMessage;
use Illuminate\Support\Collection;

interface SocialChannelServiceInterface
{
    public function setChannelInfo() : bool ;

    public function storeMessage(Collection $requestData) : SocialMessage ;

    public function sendMessage(SocialClient $socialClient, SocialChat $socialChat, string $message, Collection $keyboard = null);
}
