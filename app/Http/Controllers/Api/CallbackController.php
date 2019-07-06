<?php

namespace App\Http\Controllers\Api;

use App\Factories\Social\SocialCallbackFactory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CallbackController extends Controller
{
    public function __invoke(Request $request, $channelType, $channelId)
    {
        $socialChannelCallbackService = SocialCallbackFactory::factory($channelType);

        if (!isset($socialChannelCallbackService)) {
            throw new NotFoundHttpException();
        }

        $socialChannelCallbackService::handleCallback($channelId, collect($request->all()));
    }
}
