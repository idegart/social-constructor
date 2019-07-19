<?php

namespace App\Factories;

use App\Services\Social\BaseSocialService;

class SocialServiceFactory
{
    public static function factory(string $socialChannel) : BaseSocialService
    {
        $availableServices = config('social_bot.services');

        if (key_exists($socialChannel, $availableServices)) {
            $serviceClass = $availableServices[$socialChannel];

            return new $serviceClass();
        }

        return null;
    }
}
