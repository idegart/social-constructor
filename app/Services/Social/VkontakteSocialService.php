<?php

namespace App\Services\Social;

use App\Jobs\Social\HandleNewMessage;
use App\Models\Social\Socialable\BaseChannel;
use App\Models\Social\Socialable\BaseClient;
use App\Models\Social\Socialable\Vkontakte\VkontakteGroup;
use App\Models\Social\Socialable\Vkontakte\VkontakteMessage;
use App\Models\Social\Socialable\Vkontakte\VkontakteUser;
use App\Models\Social\SocialChannel;
use App\Models\Social\SocialChat;
use App\Models\Social\SocialClient;
use App\Models\Social\SocialMessage;
use App\Services\PlayService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;
use VK\Client\VKApiClient;
use VK\OAuth\VKOAuth;

class VkontakteSocialService extends BaseSocialService
{
    private $oauthService;

    public function __construct()
    {
        $this->apiService = new VKApiClient();
        $this->oauthService = new VKOAuth();
    }

    public function handleCallback($groupId, array $requestData)
    {
        $group = VkontakteGroup::findOrFail($groupId);

        switch ($requestData['type']) {
            case 'confirmation':
                $this->handleConfirmationCallback($group);
                return;
            case 'message_new':
                HandleNewMessage::dispatch($this, $group->socialChannel, $requestData);
                echo 'ok';
                return;
        }

        echo 'ok';
    }

    /**
     * @param BaseChannel|VkontakteGroup $channel
     * @return bool
     * @throws Exception
     */
    public function setChannelInfo(BaseChannel $channel) : bool
    {
        $response = $this->apiService->groups()->getById(config('services.vkontakte.client_service_key'), [
            'group_ids' => [$channel->id]
        ]);

        if (!$response || !isset($response[0])) {
            return false;
        }

        $channelInfo = collect($response[0])->only(['id', 'name', 'screen_name', 'is_closed', 'photo_200']);

        $channel->setRawAttributes($channelInfo->toArray());

        return true;
    }

    /**
     * @param BaseClient|VkontakteUser $client
     * @return bool
     * @throws Exception
     */
    public function setClientInfo(BaseClient $client): bool
    {
        $response = $this->apiService->users()->get(config('services.vkontakte.client_service_key'), [
            'user_ids' => [$client->id]
        ]);

        if (!$response || !isset($response[0])) {
            return false;
        }

        $clientInfo = collect($response[0])->only(['id', 'first_name', 'last_name', 'is_closed']);

        $client->setRawAttributes($clientInfo->toArray());

        return true;
    }


    public function handleAccessCallback(array $requestData)
    {
        $groupId = $requestData['state'];
        $code = $requestData['code'];

        $group = VkontakteGroup::findOrFail($groupId);

        $response = $this->oauthService->getAccessToken(
            config('services.vkontakte.client_id'),
            config('services.vkontakte.client_secret'),
            config('services.vkontakte.redirect_group'),
            $code
        );

        $access_token = $response['access_token_' . $groupId];

        $group->_access_token = $access_token;
        $group->save();

        $this->setCallbackConfirmationCode($group);
        $this->addCallbackServer($group);
        $this->setCallbackSettings($group);
    }

    private function setCallbackConfirmationCode(VkontakteGroup $group)
    {
        $result = $this->apiService->groups()->getCallbackConfirmationCode($group->_access_token, [
            'group_id' => $group->id,
        ]);

        $group->_confirmation_code = $result['code'];

        return $group->save();
    }

    public function addCallbackServer(VkontakteGroup $group)
    {
        $result = $this->apiService->groups()->addCallbackServer($group->_access_token, [
            'group_id' => $group->id,
            'url' => $group->getCallbackUrl(),
            'title' => config('app.name'),
            'secret_key' => Str::random(),
        ]);

        $group->_server_id = $result['server_id'];

        return $group->save();
    }

    public function setCallbackSettings(VkontakteGroup $group)
    {
        $result = $this->apiService->groups()->setCallbackSettings($group->_access_token, [
            'group_id' => $group->id,
            'server_id' => $group->_server_id,
            'api_version' => '5.100',
            'message_new' => 1,
        ]);

        return !! $result;
    }

    public function handleConfirmationCallback(VkontakteGroup $group)
    {
        echo $group->_confirmation_code;

        return;
    }

    public function handleNewMessageCallback(SocialChannel $socialChannel, array $requestData)
    {
        $vkontakteUser = VkontakteUser::firstOrCreate([
            'id' => $requestData['object']['from_id']
        ]);

        $socialChannel->socialClients()->syncWithoutDetaching($vkontakteUser->socialClient);

        $socialMessage = $this->storeMessage($socialChannel, $vkontakteUser->socialClient, $this->messageFormat($requestData['object']));

        $playService = new PlayService($this);

        $playService
            ->setSocialChannel($socialChannel)
            ->setSocialClient($vkontakteUser->socialClient)
            ->setSocialMessage($socialMessage)
            ->handleNewMessageCallback();
    }

    public function messageFormat(array $messageData) : array
    {
        return [
            'id' => $messageData['id'],
            'from_id' => $messageData['from_id'],
            'peer_id' => $messageData['peer_id'],
            'text' => $messageData['text'],
            'date' => Carbon::parse($messageData['date'])->toDateTimeString(),
            'attachments' => json_encode($messageData['attachments']),
            'important' => $messageData['important'],
            'out' => $messageData['out'],
        ];
    }

    public function storeMessage(SocialChannel $socialChannel, SocialClient $socialClient, $formatMessage) : SocialMessage
    {
        /** @var SocialChat $socialChat */
        $socialChat = $socialChannel->socialChats()->firstOrCreate([
            'social_client_id' => $socialClient->id,
            'social_channel_id' => $socialChannel->id,
        ]);

        $vkontakteMessage = VkontakteMessage::create($formatMessage);

        $socialChat->socialMessages()->syncWithoutDetaching($vkontakteMessage->socialMessage);

        return $vkontakteMessage->socialMessage;
    }

    public function sendMessage(SocialChannel $socialChannel, SocialClient $socialClient, string $message, ?SocialKeyboard $keyboard = null)
    {
        $messageDataSend = collect([
            'user_id' => $socialClient->client->getKey(),
            'random_id' => 0,
            'message' => $message
        ]);

        if ($keyboard) {
            $messageDataSend->put('keyboard', json_encode([
                'one_time' => true,
                'buttons' => $keyboard->buttons->map(function (SocialKeyboardButton $button) {
                    return [
                        [
                            'action' => [
                                'type' => $button->type,
                                'label' => $button->label
                            ]
                        ]
                    ];
                }),
            ]));
        }

        /** @var VkontakteGroup $vkontakteGroup */
        $vkontakteGroup = $socialChannel->channel;

        $messageId = $this->apiService->messages()->send($vkontakteGroup->_access_token, $messageDataSend->toArray());

        $response = $this->apiService->messages()->getById($vkontakteGroup->_access_token, [
            'message_ids' => [$messageId],
            'group_id' => $vkontakteGroup->id,
        ]);

        $this->storeMessage($socialChannel, $socialClient, $this->messageFormat($response['items'][0]));
    }
}
