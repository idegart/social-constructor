<?php

namespace App\Models\Social\Socialable\Telegram;

use App\Models\Social\Socialable\BaseChannel;
use App\Services\Social\TelegramSocialService;
use Exception;

class TelegramChannel extends BaseChannel
{
    protected $fillable = [
        '_access_token',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (TelegramChannel $channel) {
            return $channel->setChannelInfo();
        });
    }

    public function getChannelPhoto(): string
    {
        return 'https://via.placeholder.com/150?text=' . $this->getChannelName();
    }
    public function getChannelName(): string
    {
        return $this->first_name;
    }
    public function getChannelLink(): string
    {
        return 'https://t.me/' . $this->username;
    }
    public function getCallbackUrl(): string
    {
        $app_callback = config('app.callback');

        return "{$app_callback}/telegram/{$this->id}";
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
     * @throws Exception
     */
    public function setChannelInfo(): bool
    {
        $service = new TelegramSocialService();

        return $service->setChannelInfo($this);
    }
}
