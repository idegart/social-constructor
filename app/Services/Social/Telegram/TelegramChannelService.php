<?php

namespace App\Services\Social\Telegram;

use App\Models\Social\SocialChat;
use App\Models\Social\SocialClient;
use App\Models\Social\SocialMessage;
use App\Models\Social\Telegram\Channel;
use App\Models\Social\Telegram\Client;
use App\Models\Social\Telegram\Message;
use App\Services\Social\Interfaces\SocialChannelServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Telegram\Bot\Api;

class TelegramChannelService implements SocialChannelServiceInterface
{
    public $telegramChannel;

    public $telegramApi;

    public function __construct(Channel $telegramChannel)
    {
        $this->telegramChannel = $telegramChannel;

        $this->telegramApi = new Api($telegramChannel->_access_token);

    }

    public function setChannelInfo(): bool
    {
        $response = $this->telegramApi->getMe();

        $this->telegramChannel->first_name = $response->getFirstName();
        $this->telegramChannel->username = $response->getUsername();

        $this->telegramApi->setWebhook([
            'url' => $this->telegramChannel->getCallbackUrl()
        ]);

        return true;
    }

    public function storeMessage(Collection $requestData): SocialMessage
    {
        $messageData = $requestData->get('message');

        $from = collect($messageData['from']);

        $tgClient = Client::firstOrCreate([
            'id' => $from->get('id'),
        ], collect($from)->only(['is_bot', 'first_name', 'username']));

        /** @var SocialClient $socialClient */
        $socialClient = $tgClient->socialClient()->firstOrCreate([]);

        $socialChannel = $this->telegramChannel->socialChannel;

        $socialChannel->socialClients()->syncWithoutDetaching($socialClient);

        /** @var SocialChat $socialChat */
        $socialChat = $socialChannel->socialChats()->firstOrCreate([
            'social_client_id' => $socialClient->id,
            'social_channel_id' => $socialChannel->id,
        ]);

        return $this->storeMessageData($socialClient, $socialChat, collect($messageData));
    }

    public function sendMessage(SocialClient $socialClient, SocialChat $socialChat, string $message, Collection $keyboard = null)
    {
        // TODO: Implement sendMessage() method.
    }


    private function storeMessageData(SocialClient $socialClient, SocialChat $socialChat, Collection $messageData)
    {
        $tgMessage = Message::create([
            'id' => $messageData->get('message_id'),
            'from_id' => $messageData->get('from')['id'],
            'text' => $messageData->get('text'),
            'date' => Carbon::parse($messageData->get('date'))->toDateTimeString(),
        ]);

        /** @var SocialMessage $socialMessage */
        $socialMessage = $tgMessage->socialMessage()->create([
            'social_client_id' => $socialClient->id,
            'social_chat_id' => $socialChat->id,
        ]);

        return $socialMessage;
    }

}
