<?php

namespace App\Http\Controllers\Api;

use App\Factories\SocialServiceFactory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CallbackController extends Controller
{
    public function __invoke(Request $request, $channelType, $channelId)
    {
        $socialService = SocialServiceFactory::factory($channelType);

        if (!isset($socialService)) {
            throw new NotFoundHttpException();
        }

        $socialService->handleCallback($channelId, $request->all(), $request->getContent());
    }
}
