<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;

class ProfileController extends Controller
{
    public function scripts()
    {
        /** @var User $user */
        $user = Auth::user();

        $scripts = $user->scripts;

        return view('pages.profile.scripts', compact('scripts'));
    }

    public function socialChannels()
    {
        /** @var User $user */
        $user = Auth::user();

        $socialChannels = $user->socialChannels->loadCount('scripts');

        return view('pages.profile.socialChannels', compact('socialChannels'));
    }
}
