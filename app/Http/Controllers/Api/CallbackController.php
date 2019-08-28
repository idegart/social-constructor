<?php

namespace App\Http\Controllers\Api;

use App\Factories\SocialServiceFactory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CallbackController extends Controller
{
    public function __invoke(Request $request, $channelType, $channelId)
    {
        Log::info('start', ['channelType' => $channelType, 'channelId' => $channelId]);

        $socialService = SocialServiceFactory::factory($channelType);

        Log::info('social service');

        if (!isset($socialService)) {
            throw new NotFoundHttpException();
        }

        Log::info('not through');

        Log::info('all', $request->all());

        Log::info('content', $request->getContent());

        $socialService->handleCallback($channelId, $request->all(), $request->getContent());
    }
}
