<?php

namespace App\Http\Controllers;

use App\Models\Social\BaseChannel;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CallbackController extends Controller
{
    public function __invoke(Request $request, $channelType, $channelId)
    {
        $channels = config('channels');

        if (!isset($channels[$channelType])) {
            throw new NotFoundHttpException();
        }

        $channelClassName = config('channels')[$channelType];

        /** @var BaseChannel $channelClass */
        $channelClass = $channelClassName::findOrFail($channelId);

        $channelClass->init($request);
    }
}
