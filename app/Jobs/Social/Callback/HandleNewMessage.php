<?php

namespace App\Jobs\Social\Callback;

use App\Services\Social\Interfaces\SocialChannelCallbackServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Collection;

class HandleNewMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $callbackService;

    public $requestData;

    public function __construct(SocialChannelCallbackServiceInterface $callbackService, Collection $requestData)
    {
        $this->callbackService = $callbackService;

        $this->requestData = $requestData;
    }

    public function handle()
    {
        $this->callbackService->handleNewMessageCallback($this->requestData);
    }
}
