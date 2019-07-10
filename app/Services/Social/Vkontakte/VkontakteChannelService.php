<?php

namespace App\Services\Social\Vkontakte;

use App\Models\Social\SocialBase\BaseClient;
use App\Models\Social\SocialChat;
use App\Models\Social\SocialClient;
use App\Models\Social\SocialMessage;
use App\Models\Social\Vkontakte\Channel;
use App\Models\Social\Vkontakte\Client;
use App\Models\Social\Vkontakte\Message;
use App\Services\Social\Interfaces\SocialChannelServiceInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use VK\Client\VKApiClient;

class VkontakteChannelService implements SocialChannelServiceInterface
{
    public $vkontakteChannel;

    public $vkApi;

    public function __construct(Channel $vkontakteChannel)
    {
        $this->vkontakteChannel = $vkontakteChannel;

        $this->vkApi = new VKApiClient();
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function setChannelInfo(): bool
    {
        $response = $this->vkApi->groups()->getById(config('services.vkontakte.client_service_key'), [
            'group_ids' => [$this->vkontakteChannel->id]
        ]);

        if (!$response || !isset($response[0])) {
            return false;
        }

        $channelInfo = collect($response[0])->only(['id', 'name', 'screen_name', 'is_closed', 'photo_200']);

        $this->vkontakteChannel->setRawAttributes($channelInfo->toArray());

        return true;
    }

    public function storeMessage(Collection $requestData): SocialMessage
    {
        $messageData = collect($requestData->get('object'));

        $userId = $messageData->get('from_id');

        /** @var Client $vkClient */
        $vkClient = Client::firstOrCreate(['id' => $userId]);

        /** @var SocialClient $socialClient */
        $socialClient = $vkClient->socialClient()->firstOrCreate([]);

        $socialChannel = $this->vkontakteChannel->socialChannel;

        $socialChannel->socialClients()->syncWithoutDetaching($socialClient);

        /** @var SocialChat $socialChat */
        $socialChat = $socialChannel->socialChats()->firstOrCreate([
            'social_client_id' => $socialClient->id,
            'social_channel_id' => $socialChannel->id,
        ]);

        return $this->storeMessageData($socialClient, $socialChat, $messageData);
    }

    public function sendMessage(SocialClient $socialClient, SocialChat $socialChat, string $message, Collection $keyboard = null)
    {
        $messageDataSend = collect([
            'user_id' => $socialClient->client->getKey(),
            'random_id' => 0,
            'peer_id' => $socialClient->client->getKey(),
            'message' => $message
        ]);

        if ($keyboard) {
            $messageDataSend->put('keyboard', json_encode([
                'one_time' => true,
                'buttons' => $keyboard->map(function ($button) {
                    return [
                        [
                            'action' => [
                                'type' => 'text',
                                'label' => $button['label']
                            ]
                        ]
                    ];
                }),
            ]));
        }

        $messageId = $this->vkApi->messages()->send($this->vkontakteChannel->_access_token, $messageDataSend->toArray());

        $messageData = collect($this->getMessageById($messageId));

        $this->storeMessageData($socialClient, $socialChat, $messageData);
    }

    public function getMessageById($messageId)
    {
        $response = $this->vkApi->messages()->getById($this->vkontakteChannel->_access_token, [
            'message_ids' => [$messageId],
            'group_id' => $this->vkontakteChannel->id,
        ]);

        return $response['items'][0];
    }

    private function storeMessageData(SocialClient $socialClient, SocialChat $socialChat, Collection $messageData)
    {
        $vkMessage = Message::create([
            'id' => $messageData->get('id'),
            'from_id' => $messageData->get('from_id'),
            'peer_id' => $messageData->get('peer_id'),
            'text' => $messageData->get('text'),
            'date' => Carbon::parse($messageData->get('date'))->toDateTimeString(),
            'attachments' => json_encode($messageData->get('attachments')),
            'important' => $messageData->get('important'),
            'out' => $messageData->get('out'),
        ]);

        /** @var SocialMessage $socialMessage */
        $socialMessage = $vkMessage->socialMessage()->create([
            'social_client_id' => $socialClient->id,
            'social_chat_id' => $socialChat->id,
        ]);

        return $socialMessage;
    }



    // ================================================================================
    // EXTRA FUNCTIONS
    // ================================================================================

    /**
     * @return bool
     * @throws Exception
     */
    public function setCallbackConfirmationCode()
    {
        $result = $this->vkApi->groups()->getCallbackConfirmationCode($this->vkontakteChannel->_access_token, [
            'group_id' => $this->vkontakteChannel->id,
        ]);

        $this->vkontakteChannel->_confirmation_code = $result['code'];

        return $this->vkontakteChannel->save();
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function addCallbackServer()
    {
        $result = $this->vkApi->groups()->addCallbackServer($this->vkontakteChannel->_access_token, [
            'group_id' => $this->vkontakteChannel->id,
            'url' => $this->vkontakteChannel->getCallbackUrl(),
            'title' => config('app.name'),
            'secret_key' => Str::random(),
        ]);

        $this->vkontakteChannel->_server_id = $result['server_id'];

        return $this->vkontakteChannel->save();
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function setCallbackSettings()
    {
        $result = $this->vkApi->groups()->setCallbackSettings($this->vkontakteChannel->_access_token, [
            'group_id' => $this->vkontakteChannel->id,
            'server_id' => $this->vkontakteChannel->_server_id,
            'api_version' => '5.101',
            'message_new' => 1,
        ]);

        return !! $result;
    }
}
