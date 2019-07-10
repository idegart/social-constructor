<?php

namespace App\Services;

class PlayService
{
    const MAX_STEPS = 25;

    protected $totalSteps = 0;

    protected $socialService;

    public function __construct(SocialServiceInterface $socialService)
    {
        $this->socialService = $socialService;
    }

    public function handleMessage($messageData)
    {
        $this->socialService->handleMessage($messageData);
    }
}
