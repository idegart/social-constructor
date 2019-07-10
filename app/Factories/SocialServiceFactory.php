<?php

namespace App\Factories;

use App\Services\Social\BaseSocialService;
use App\Services\Social\TelegramSocialService;
use App\Services\Social\VkontakteSocialService;

class SocialServiceFactory
{
    public static function factory(string $socialChannel) : BaseSocialService
    {
        switch ($socialChannel) {
            case 'vkontakte':
                return new VkontakteSocialService();
            case 'telegram':
                return new TelegramSocialService();
            default:
                return null;
        }
    }
}
