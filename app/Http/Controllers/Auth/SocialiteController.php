<?php

namespace App\Http\Controllers\Auth;

use App\Factories\Social\SocialCallbackFactory;
use App\Http\Requests\Auth\Socialite\RedirectRequest;
use App\Models\User;
use Auth;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Manager\OAuth2\User as SocialiteUser;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @return RedirectResponse
     * @throws AuthenticationException
     */
    public function handleProviderCallback($driver)
    {
        /** @var SocialiteUser $socialUser */
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

    public function handleSocialChannelAccessCallback(Request $request, $channelType)
    {
        $socialChannelCallbackService = SocialCallbackFactory::factory($channelType);

        if (!isset($socialChannelCallbackService)) {
            throw new NotFoundHttpException();
        }

        $socialChannelCallbackService->handleAccessCallback(collect($request->all()));

        return redirect()->route('profiles.socialChannels');
    }
}
