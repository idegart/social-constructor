<?php

namespace App\Services\Social;

use App\Models\Social\Socialable\BaseChannel;
use App\Models\Social\Socialable\BaseClient;
use App\Models\Social\SocialChannel;
use App\Models\Social\SocialClient;
use App\Models\Social\SocialMessage;

abstract class BaseSocialService
{
    protected $apiService;

    abstract public function setChannelInfo(BaseChannel $channel) : bool ;

    abstract public function setClientInfo(BaseClient $client) : bool ;

    abstract public function handleAccessCallback(array $requestData);

    abstract public function handleCallback($channelId, array $requestData);

    abstract public function handleNewMessageCallback(SocialChannel $socialChannel, array $requestData);

    abstract public function messageFormat(array $messageData) : array ;

    abstract public function sendMessage(SocialChannel $socialChannel, SocialClient $socialClient, string $message, ?SocialKeyboard $keyboard = null);

    abstract public function storeMessage(SocialChannel $socialChannel, SocialClient $socialClient, $formatMessage) : SocialMessage;
}
