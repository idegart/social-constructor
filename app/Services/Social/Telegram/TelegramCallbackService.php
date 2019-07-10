<?php

namespace App\Services\Social\Telegram;

use App\Jobs\Social\Callback\HandleNewMessage;
use App\Models\Social\Telegram\Channel;
use App\Services\Social\Interfaces\SocialChannelCallbackServiceInterface;
use App\Services\Social\SocialChatService;
use Illuminate\Support\Collection;
use Telegram\Bot\Api;

class TelegramCallbackService implements SocialChannelCallbackServiceInterface
{
    /** @var Channel */
    public $channel;

    public $telegramApi;

    public function setChannel(Channel $channel)
    {
        $this->channel = $channel;

        $this->telegramApi = new Api($channel->_access_token);
    }

    public static function handleCallback($channelId, Collection $requestData): void
    {
        $channel = Channel::findOrFail($channelId);

        $callbackService = new self();

        $callbackService->setChannel($channel);

        HandleNewMessage::dispatch($callbackService, $requestData);

        echo 'ok';
    }

    public function handleAccessCallback(Collection $requestData)
    {
        return null;
    }

    public function handleNewMessageCallback(Collection $requestData)
    {
        $service = new TelegramChannelService($this->channel);

        $socialMessage = $service->storeMessage($requestData);

        $socialChatService = new SocialChatService($this->channel->socialChannel, $socialMessage->socialClient);

        $socialChatService->handleNewMessage($socialMessage);
    }

}
