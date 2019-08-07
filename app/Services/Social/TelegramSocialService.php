<?php

namespace App\Services\Social;

use App\Jobs\Social\HandleNewMessage;
use App\Models\Social\Socialable\BaseChannel;
use App\Models\Social\Socialable\BaseClient;
use App\Models\Social\Socialable\Telegram\TelegramChannel;
use App\Models\Social\Socialable\Telegram\TelegramMessage;
use App\Models\Social\Socialable\Telegram\TelegramUser;
use App\Models\Social\SocialChannel;
use App\Models\Social\SocialChat;
use App\Models\Social\SocialClient;
use App\Models\Social\SocialMessage;
use App\Services\PlayService;
use Carbon\Carbon;
use Exception;
use Telegram\Bot\Api;

class TelegramSocialService extends BaseSocialService
{
    /**
     * @param BaseChannel|TelegramChannel $channel
     * @return bool
     * @throws Exception
     */
    public function setChannelInfo(BaseChannel $channel): bool
    {
        $this->apiService = new Api($channel->_access_token);

        $response = $this->apiService->getMe();

        $channel->id = $response->getId();
        $channel->first_name = $response->getFirstName();
        $channel->username = $response->getUsername();

        $this->apiService->setWebhook([
            'url' => $channel->getCallbackUrl()
        ]);

        return true;
    }

    public function setClientInfo(BaseClient $client): bool
    {
        return true;
    }

    public function handleAccessCallback(array $requestData)
    {
        return null;
    }

    public function handleCallback($channelId, array $requestData)
    {
        $channel = TelegramChannel::query()->where('id', '=', $channelId)->firstOrFail();

        HandleNewMessage::dispatch($this, $channel->socialChannel, $requestData);

        echo 'ok';
    }

    public function handleNewMessageCallback(SocialChannel $socialChannel, array $requestData)
    {
        $messageData = $requestData['message'];

        $from = collect($messageData['from']);

        $telegramUser = TelegramUser::firstOrCreate([
            'id' => $from->get('id')
        ], collect($from)->only(['is_bot', 'first_name', 'username'])->toArray());

        $socialChannel->socialClients()->syncWithoutDetaching($telegramUser->socialClient);

        $socialMessage = $this->storeMessage($socialChannel, $telegramUser->socialClient, $this->messageFormat($requestData['message']));

        $playService = new PlayService($this);

        $playService
            ->setSocialChannel($socialChannel)
            ->setSocialClient($telegramUser->socialClient)
            ->setSocialMessage($socialMessage)
            ->handleNewMessageCallback();
    }

    public function messageFormat(array $messageData): array
    {
        return [
            'id' => $messageData['message_id'],
            'from_id' => $messageData['from']['id'],
            'text' => $messageData['text'],
            'date' => Carbon::parse($messageData['date'])->toDateTimeString(),
            'is_out' => false,
        ];
    }

    public function storeMessage(SocialChannel $socialChannel, SocialClient $socialClient, $formatMessage): SocialMessage
    {
        /** @var SocialChat $socialChat */
        $socialChat = $socialChannel->socialChats()->firstOrCreate([
            'social_client_id' => $socialClient->id,
            'social_channel_id' => $socialChannel->id,
        ]);

        $telegramMessage = TelegramMessage::create($formatMessage);

        $socialChat->socialMessages()->syncWithoutDetaching($telegramMessage->socialMessage);

        return $telegramMessage->socialMessage;
    }

    /**
     * @param SocialChannel $socialChannel
     * @param SocialClient $socialClient
     * @param string $message
     * @param SocialKeyboard|null $keyboard
     * @throws Exception
     */
    public function sendMessage(SocialChannel $socialChannel, SocialClient $socialClient, string $message, ?SocialKeyboard $keyboard = null)
    {
        /** @var TelegramChannel $telegramChannel */
        $telegramChannel = $socialChannel->channel;

        $this->apiService = new Api($telegramChannel->_access_token);

        $messageDataSend = collect([
            'chat_id' => $socialClient->client->id,
            'text' => $message
        ]);

        if ($keyboard) {
            $reply_markup = $this->apiService->replyKeyboardMarkup([
                'keyboard' => $keyboard->buttons->map(function (SocialKeyboardButton $button) {
                    return [$button->label];
                }),
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ]);

            $messageDataSend->put('reply_markup', $reply_markup);
        }

        $response = $this->apiService->sendMessage($messageDataSend->toArray());

        $this->storeMessage($socialChannel, $socialClient, [
            'id' => $response->getMessageId(),
            'from_id' => $telegramChannel->id,
            'text' => $message,
            'date' => Carbon::now()->toDateTimeString(),
            'is_out' => true,
        ]);
    }

}
