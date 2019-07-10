<?php

namespace App\Models\Social\Socialable\Vkontakte;

use App\Models\Social\Socialable\BaseChannel;
use App\Services\Social\VkontakteSocialService;
use Exception;
use VK\OAuth\Scopes\VKOAuthGroupScope;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\VKOAuthResponseType;

class VkontakteGroup extends BaseChannel
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function (VkontakteGroup $group) {
            return $group->setChannelInfo();
        });
    }

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

        $url = $oauth->getAuthorizeUrl(
            VKOAuthResponseType::CODE,
            config('services.vkontakte.client_id'),
            config('services.vkontakte.redirect_group'),
            VKOAuthDisplay::PAGE,
            [VKOAuthGroupScope::MESSAGES, VKOAuthGroupScope::MANAGE],
            $this->getKey(),
            [$this->getKey()]
        );

        return $url;
    }

    public function hasAccessToken(): bool
    {
        return !! $this->_access_token;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function setChannelInfo(): bool
    {
        $service = new VkontakteSocialService();

        return $service->setChannelInfo($this);
    }
}
