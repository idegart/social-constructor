<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Social\StoreRequest;
use App\Models\Script;
use App\Models\Social\SocialChannel;
use App\Models\Social\Vkontakte\VkontakteChannel;
use App\Models\User;
use App\Http\Controllers\Controller;
use Auth;

class SocialChannelController extends Controller
{
    public function store(StoreRequest $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $channelType = $request->get('type');

        $channelClassName = config('channels')[$channelType];

        switch ($channelType) {
            case 'vkontakte':
                /** @var VkontakteChannel $socialChannel */
                $socialChannel = $channelClassName::firstOrCreate([
                    'id' => $request->get('vk_group_id')
                ]);
                break;
            default:
                return $this->errorResponse();
        }



        $user->socialChannels()->updateOrCreate([
            'channel_id' => $socialChannel->getKey(),
            'channel_type' => $socialChannel->getMorphClass(),
        ], [
            'user_id' => $user->id,
        ]);

        return $this->successResponse();
    }

    public function attachScript(SocialChannel $socialChannel, Script $script)
    {
        $socialChannel->scripts()->syncWithoutDetaching($script);

        return $this->successResponse();
    }

    public function detachScript(SocialChannel $socialChannel, $scriptId)
    {
        $script = Script::findOrFail($scriptId);

        $socialChannel->scripts()->detach($script);

        return $this->successResponse();
    }
}
