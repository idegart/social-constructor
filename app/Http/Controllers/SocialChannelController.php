<?php

namespace App\Http\Controllers;

use App\Models\Social\SocialChannel;
use Illuminate\Http\Request;

class SocialChannelController extends Controller
{
    public function show(SocialChannel $socialChannel)
    {
        $socialChannel->load('scripts');

        return view('pages.socialChannel.show', compact('socialChannel'));
    }
}
