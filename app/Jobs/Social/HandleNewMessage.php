<?php

namespace App\Jobs\Social;

use App\Models\Social\SocialChannel;
use App\Services\Social\BaseSocialService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class HandleNewMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $socialService;

    private $socialChannel;

    private $requestData;

    public function __construct(BaseSocialService $socialService, SocialChannel $socialChannel, array $requestData)
    {
        $this->socialService = $socialService;

        $this->socialChannel = $socialChannel;

        $this->requestData = $requestData;
    }

    public function handle()
    {
        $this->socialService->handleNewMessageCallback($this->socialChannel, $this->requestData);
    }
}
