<?php

namespace App\Models\Social\Vkontakte;

use App\Models\Social\SocialBase\BaseChannel;
use App\Services\Social\Vkontakte\VkontakteChannelService;
use Auth;
use Exception;
use VK\OAuth\Scopes\VKOAuthGroupScope;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\VKOAuthResponseType;

class Channel extends BaseChannel
{
    protected $table = 'vkontakte_channels';

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Channel $channel) {
            return $channel->setChannelInfo();
        });

        static::created(function (BaseChannel $channel) {
            $channel->socialChannel()->create([
                'user_id' => Auth::id()
            ]);
        });
    }

    // ================================================================================
    // ATTRIBUTE ACCESS
    // ================================================================================

    public function getChannelPhoto(): string
    {
        return $this->photo_200;
    }

    public function getChannelName(): string
    {
        return $this->name;
    }

    public function getChannelLink(): string
    {
        return 'https://vk.com/' . $this->screen_name;
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

    public function hasAccessToken(): bool
    {
        return !! $this->_access_token;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function setChannelInfo() : bool
    {
        $service = new VkontakteChannelService($this);

        return $service->setChannelInfo();
    }


}
