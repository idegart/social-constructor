<?php

namespace App\Services\Social\Interfaces;

use Illuminate\Support\Collection;

interface SocialChannelCallbackServiceInterface
{
    public static function handleCallback($channelId, Collection $requestData) : void ;

    public function handleAccessCallback(Collection $requestData);

    public function handleNewMessageCallback(Collection $requestData);
}
