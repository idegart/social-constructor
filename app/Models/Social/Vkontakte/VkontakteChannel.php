<?php

namespace App\Models\Social\Vkontakte;

use App\Models\Social\BaseChannel;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Log;
use VK\Client\VKApiClient;
use VK\OAuth\Scopes\VKOAuthGroupScope;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\VKOAuthResponseType;

class VkontakteChannel extends BaseChannel
{
    protected $table = 'vkontakte_channels';

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (VkontakteChannel $vkontakteChannel) {
            return $vkontakteChannel->setChannelInfo();
        });
    }

    public function getChannelPhoto() : string
    {
        return $this->photo_200;
    }

    public function getChannelName() : string
    {
        return $this->name;
    }

    public function getChannelLink() : string
    {
        return 'https://vk.com/' . $this->screen_name;
    }

    public function setChannelInfo() : bool
    {
        $vkApi = new VKApiClient();

        $response = $vkApi->groups()->getById(config('services.vkontakte.client_service_key'), [
            'group_ids' => [$this->getKey()]
        ]);

        if (!$response || !isset($response[0])) {
            return false;
        }

        $channelInfo = $response[0];

        $this->name = $channelInfo['name'];
        $this->screen_name = $channelInfo['screen_name'];
        $this->is_closed = $channelInfo['is_closed'];
        $this->photo_200 = $channelInfo['photo_200'];

        return true;
    }

    public function hasAccessToken(): bool
    {
        return !! $this->_access_token;
    }

    public function getCallbackUrl(): string
    {
        $app_callback = config('app.callback');

        return "{$app_callback}/vkontakte/{$this->id}";
    }


    public function getAccessLink(): string
    {
        $oauth = new VKOAuth();
        $client_id = config('services.vkontakte.client_id');
        $redirect_uri = config('services.vkontakte.redirect_group');
        $display = VKOAuthDisplay::PAGE;
        $scope = [VKOAuthGroupScope::MESSAGES, VKOAuthGroupScope::MANAGE];
        $state = $this->getKey();
        $groups_ids = [$this->getKey()];

        return $oauth->getAuthorizeUrl(
            VKOAuthResponseType::CODE,
            $client_id, $redirect_uri, $display, $scope, $state, $groups_ids
        );
    }

    public static function handleAccessCallback(Request $request)
    {
        $code = $request->get('code');
        $groupId = $request->get('state');

        $vkontakteChannel = VkontakteChannel::findOrFail($groupId);

        $oauth = new VKOAuth();
        $client_id = config('services.vkontakte.client_id');
        $client_secret = config('services.vkontakte.client_secret');
        $redirect_uri = config('services.vkontakte.redirect_group');

        $response = $oauth->getAccessToken($client_id, $client_secret, $redirect_uri, $code);

        $access_token = $response['access_token_' . $groupId];

        $vkontakteChannel->_access_token = $access_token;
        $vkontakteChannel->save();

        $vkontakteChannel->setCallbackConfirmationCode();
        $vkontakteChannel->addCallbackServer();
        $vkontakteChannel->setCallbackSettings();
    }

    public function setCallbackConfirmationCode()
    {
        $vkApi = new VKApiClient();

        $result = $vkApi->groups()->getCallbackConfirmationCode($this->_access_token, [
            'group_id' => $this->id,
        ]);

        $this->_confirmation_code = $result['code'];

        return $this->save();
    }

    public function addCallbackServer()
    {
        $vkApi = new VKApiClient();

        $result = $vkApi->groups()->addCallbackServer($this->_access_token, [
            'group_id' => $this->id,
            'url' => $this->getCallbackUrl(),
            'title' => config('app.name'),
            'secret_key' => Str::random(),
        ]);

        $this->_server_id = $result['server_id'];

        return $this->save();
    }

    public function setCallbackSettings()
    {
        $vkApi = new VKApiClient();

        $result = $vkApi->groups()->setCallbackSettings($this->_access_token, [
            'group_id' => $this->id,
            'server_id' => $this->_server_id,
            'message_new' => 1,
        ]);

        return !! $result;
    }

    /**
     * @param Request $request
     */
    public function init(Request $request) : void
    {
        switch ($request->get('type')) {
            case 'confirmation':
                echo $this->_confirmation_code;
                return;
        }

        echo 'ok';
    }
}
