<?php

namespace App\Models\Social\Socialable\Chat2Desk;

use App\Models\Social\Socialable\BaseChannel;
use App\Services\Social\Chat2DeskSocialService;
use GuzzleHttp\Exception\GuzzleException;

class Chat2DeskChannel extends BaseChannel
{
    protected $table = 'chat_2_desk_channels';

    public $incrementing = true;

    protected $fillable = [
        '_access_token',
        '_operator_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Chat2DeskChannel $channel) {
            return $channel->setChannelInfo();
        });

        static::created(function (Chat2DeskChannel $channel) {
            $channel->setChannelWebhook();
        });
    }

    public function getChannelPhoto(): string
    {
        return 'https://via.placeholder.com/150?text=' . $this->getChannelName();
    }

    public function getChannelName(): string
    {
        return $this->company_name;
    }

    public function getChannelLink(): string
    {
        return '';
    }

    public function getCallbackUrl(): string
    {
        $app_callback = config('app.callback');

        return "{$app_callback}/chat2desk/{$this->_id}";
    }

    public function getAccessLink(): string
    {
        return '';
    }

    public function hasAccessToken(): bool
    {
        return !! $this->_access_token;
    }

    /**
     * @return bool
     * @throws GuzzleException
     */
    public function setChannelInfo(): bool
    {
        $service = new Chat2DeskSocialService();

        return $service->setChannelInfo($this);
    }

    public function setChannelWebhook()
    {
        $service = new Chat2DeskSocialService();

        return $service->setChannelWebhook($this);
    }

    public function getRealId(): string
    {
        return null;
    }

    public function getSocialType(): string
    {
        return null;
    }


}
