<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Social\StoreRequest;
use App\Models\Script;
use App\Models\Social\SocialChannel;
use App\Models\Social\Vkontakte\Channel as VkontakteChannel;
use App\Http\Controllers\Controller;

class SocialChannelController extends Controller
{
    public function store(StoreRequest $request)
    {
        $channelType = $request->get('type');

        $channelClassName = config('channels')[$channelType];

        switch ($channelType) {
            case 'vkontakte':
                /** @var VkontakteChannel $socialChannel */
                $channelClassName::firstOrCreate([
                    'id' => $request->get('vk_group_id')
                ]);
                break;
            default:
                return $this->errorResponse();
        }

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
