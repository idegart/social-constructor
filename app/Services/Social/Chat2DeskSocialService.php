<?php

namespace App\Services\Social;

use App\Jobs\Social\HandleNewMessage;
use App\Models\Social\Socialable\BaseChannel;
use App\Models\Social\Socialable\BaseClient;
use App\Models\Social\Socialable\Chat2Desk\Chat2DeskChannel;
use App\Models\Social\Socialable\Chat2Desk\Chat2DeskMessage;
use App\Models\Social\Socialable\Chat2Desk\Chat2DeskUser;
use App\Models\Social\SocialChannel;
use App\Models\Social\SocialClient;
use App\Models\Social\SocialMessage;
use App\Services\PlayService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Throwable;

class Chat2DeskSocialService extends BaseSocialService
{
    const API_URL = 'https://api.chat2desk.com/v1/';

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
        $channel = Chat2DeskChannel::query()->where('_id', '=', $channelId)->firstOrFail();

        switch ($requestData['type']) {
            case 'from_client':
                HandleNewMessage::dispatch($this, $channel->socialChannel, $requestData);
                break;
        }

        echo 'ok';
    }

    public function handleNewMessageCallback(SocialChannel $socialChannel, array $requestData)
    {
        $chatUser = Chat2DeskUser::firstOrCreate([
            'id' => $requestData['client_id']
        ], collect($requestData['client'])->only([
            'name', 'phone', 'client_phone', 'name', 'assigned_name'
        ])->toArray());

        $socialChannel->socialClients()->syncWithoutDetaching($chatUser->socialClient);

        $socialMessage = $this->storeMessage($socialChannel, $chatUser->socialClient, $this->messageFormat($requestData));

        $playService = new PlayService($this);

        $playService
            ->setSocialChannel($socialChannel)
            ->setSocialClient($chatUser->socialClient)
            ->setSocialMessage($socialMessage)
            ->handleNewMessageCallback();
    }

    public function messageFormat(array $messageData): array
    {
        return [
            'id' => $messageData['message_id'],
            'text' => $messageData['text'],
            'type' => $messageData['type'],
            'client_id' => $messageData['client_id'],
            'channel_id' => $messageData['channel_id'],
            'operator_id' => $messageData['operator_id'],
            'transport' => $messageData['transport'],
        ];
    }

    public function sendMessage(SocialChannel $socialChannel, SocialClient $socialClient, string $message, ?SocialKeyboard $keyboard = null)
    {
        $client = new Client();

        /** @var Chat2DeskChannel $cdChannel */
        $cdChannel = $socialChannel->channel;

        $messageDataSend = collect([
            'client_id' => $socialClient->client->id,
            'text' => $message,
        ]);

        if ($cdChannel->_operator_id) {
            $messageDataSend->put('operator_id', $cdChannel->_operator_id);
        }

        if ($keyboard) {
            $messageDataSend->put('keyboard', [
                'buttons' => $keyboard->buttons->map(function (SocialKeyboardButton $button) {
                    return [
                        'type' => 'reply',
                        'text' => $button->label,
                    ];
                }),
            ]);
        }

        try {
            $response = $client->post(self::API_URL . 'messages', [
                'headers' => [
                    'Authorization' => $cdChannel->_access_token,
                    'Content-Type' => 'Application/JSON',
                ],
                'body' => $messageDataSend->toJson()
            ]);
        } catch (BadResponseException $exception) {
//            dump($exception->getResponse()->getBody()->getContents());
        }

        $responseArray = json_decode($response->getBody()->getContents(), true);

        $messageResponse = $responseArray['data'];
        $messageResponse['text'] = $message;

        $this->storeMessage($socialChannel, $socialClient, $this->messageFormat($messageResponse));
    }

    public function storeMessage(SocialChannel $socialChannel, SocialClient $socialClient, $formatMessage): SocialMessage
    {
        $socialChat = null;
        $tries = 0;

        while (!$socialChat && $tries < 10) {
            $tries++;
            try {
                // TODO: узнать почему ломается при создании новой записи кроме первой!
                $socialChat = $socialChannel->socialChats()->firstOrCreate([
                    'social_client_id' => $socialClient->id,
                ]);
            } catch (Throwable $exception) {}
        }

        dump('total tries: ' . $tries);

        $chatMessage = Chat2DeskMessage::create($formatMessage);

        $socialChat->socialMessages()->syncWithoutDetaching($chatMessage->socialMessage);

        return $chatMessage->socialMessage;
    }

    /**
     * @param BaseChannel|Chat2DeskChannel $channel
     * @return bool
     */
    public function setChannelInfo(BaseChannel $channel): bool
    {
        $client = new Client();

        $response = $client->get(self::API_URL . 'companies/api_info', [
            'headers' => [
                'Authorization' => $channel->_access_token
            ]
        ]);

        $responseArray = json_decode($response->getBody()->getContents(), true);

        $channel->company_name = $responseArray['data']['company_name'];

        return true;
    }

    public function setChannelWebhook(Chat2DeskChannel $channel)
    {
        $client = new Client();

        $response = $client->post(self::API_URL . 'webhooks', [
            'headers' => [
                'Authorization' => $channel->_access_token
            ],
            'form_params' => array(
                'url' => $channel->getCallbackUrl(),
                'name' => config('app.name'),
                'events' => ['inbox']
            )
        ]);

        $responseArray = json_decode($response->getBody()->getContents(), true);

        return isset($responseArray['status']) && $responseArray['status'] === 'success';
    }

}
