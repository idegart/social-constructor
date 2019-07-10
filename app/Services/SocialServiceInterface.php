<?php

namespace App\Services;

interface SocialServiceInterface
{
    public function handleMessage($messageData);

    public function sendMessage();

    public function getClientInfo();

    public function getChannelInfo();
}
