<?php

namespace App\Services\Social\Vkontakte;

use App\Models\Social\Vkontakte\Message;
use Exception;
use VK\Client\VKApiClient;

class VkontakteMessageService
{
    public $vkontakteMessage;

    public $vkApi;

    public function __construct(Message $vkontakteMessage)
    {
        $this->vkontakteMessage = $vkontakteMessage;

        $this->vkApi = new VKApiClient();
    }
}
