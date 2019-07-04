<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\Socialite\RedirectRequest;
use App\Models\User;
use Auth;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirectToProvider(RedirectRequest $request)
    {
        return Socialite::driver($driver = $request->get('provider'))
            ->scopes(config('services')[$driver]['scopes'])
            ->redirect();
    }

    /**
     * @param $driver
     * @return \Illuminate\Http\RedirectResponse
     * @throws AuthenticationException
     */
    public function handleProviderCallback($driver)
    {
        /** @var \SocialiteProviders\Manager\OAuth2\User $socialUser */
        $socialUser = Socialite::driver($driver)->user();

        if (!$socialUser->getEmail()) {
            throw new AuthenticationException('User must have Email!');
        }

        $user = User::updateOrCreate([
            'email' => $socialUser->getEmail(),
        ], [
            'name' => $socialUser->getNickname(),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
