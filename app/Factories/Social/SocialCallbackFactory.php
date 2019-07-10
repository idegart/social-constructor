<?php

namespace App\Factories\Social;

use App\Services\Social\Interfaces\SocialChannelCallbackServiceInterface;
use App\Services\Social\Telegram\TelegramCallbackService;
use App\Services\Social\Vkontakte\VkontakteCallbackService;

final class SocialCallbackFactory
{
    public static function factory(string $socialChannel) : SocialChannelCallbackServiceInterface
    {
        switch ($socialChannel) {
            case 'vkontakte':
                return new VkontakteCallbackService();
            case 'telegram':
                return new TelegramCallbackService();
        }

        return null;
    }
}
