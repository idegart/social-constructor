<?php

namespace App\Models\Social\Telegram;

use App\Models\Social\SocialBase\BaseChannel;
use App\Models\Social\SocialChat;
use App\Models\Social\SocialClient;
use App\Services\Social\Telegram\TelegramChannelService;
use Auth;
use Illuminate\Support\Collection;

class Channel extends BaseChannel
{
    protected $table = 'telegram_channels';

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


    public function sendMessage(SocialClient $socialClient, SocialChat $socialChat, string $message, Collection $keyboard)
    {
        // TODO: Implement sendMessage() method.
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
        // TODO: Implement getAccessLink() method.
    }

    public function hasAccessToken(): bool
    {
        return !! $this->_access_token;
    }

    public function setChannelInfo(): bool
    {
        $service = new TelegramChannelService($this);

        return $service->setChannelInfo();
    }

}
