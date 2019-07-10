<?php

namespace App\Services\Social\Vkontakte;

use App\Jobs\Social\Callback\HandleNewMessage;
use App\Models\Social\Vkontakte\Channel;
use App\Services\Social\Interfaces\SocialChannelCallbackServiceInterface;
use App\Services\Social\SocialChatService;
use Exception;
use Illuminate\Support\Collection;
use VK\Client\VKApiClient;
use VK\OAuth\VKOAuth;

class VkontakteCallbackService implements SocialChannelCallbackServiceInterface
{
    /** @var Channel */
    public $channel;

    public $vkApi;

    public function __construct()
    {
        $this->vkApi = new VKApiClient();
    }

    public function setChannel(Channel $channel)
    {
        $this->channel = $channel;
    }

    public static function handleCallback($channelId, Collection $requestData) : void
    {
        $channel = Channel::findOrFail($channelId);

        $callbackService = new self();

        $callbackService->setChannel($channel);

        switch ($requestData->get('type')) {
            case 'confirmation':
                $callbackService->handleConfirmationCallback();
                return;
            case 'message_new':
                HandleNewMessage::dispatch($callbackService, $requestData);
                echo 'ok';
                return;
        }

        echo 'ok';
    }


    /**
     * @param Collection $requestData
     * @throws Exception
     */
    public function handleAccessCallback(Collection $requestData)
    {
        $groupId = $requestData->get('state');
        $code = $requestData->get('code');

        $channel = Channel::findOrFail($groupId);

        $oauth = new VKOAuth();
        $client_id = config('services.vkontakte.client_id');
        $client_secret = config('services.vkontakte.client_secret');
        $redirect_uri = config('services.vkontakte.redirect_group');

        $response = $oauth->getAccessToken($client_id, $client_secret, $redirect_uri, $code);

        $access_token = $response['access_token_' . $groupId];

        $channel->_access_token = $access_token;
        $channel->save();

        $vkontakteChannelService = new VkontakteChannelService($channel);

        $vkontakteChannelService->setCallbackConfirmationCode();
        $vkontakteChannelService->addCallbackServer();
        $vkontakteChannelService->setCallbackSettings();
    }

    public function handleNewMessageCallback(Collection $requestData)
    {
        $service = new VkontakteChannelService($this->channel);

        $socialMessage = $service->storeMessage($requestData);

        $socialChatService = new SocialChatService($this->channel->socialChannel, $socialMessage->socialClient);

        $socialChatService->handleNewMessage($socialMessage);
    }


    // ================================================================================
    // EXTRA HANDLERS
    // ================================================================================

    public function handleConfirmationCallback()
    {
        echo $this->channel->_confirmation_code;

        return;
    }

}
