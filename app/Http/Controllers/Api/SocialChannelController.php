<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Social\StoreRequest;
use App\Models\Script;
use App\Models\Social\SocialChannel;
use App\Models\Social\Telegram\Channel as TelegramChannel;
use App\Models\Social\Vkontakte\Channel as VkontakteChannel;
use App\Http\Controllers\Controller;
use App\Services\Social\Telegram\TelegramChannelService;

class SocialChannelController extends Controller
{
    public function store(StoreRequest $request)
    {
        $channelType = $request->get('type');

        $channelClassName = config('channels')[$channelType];

        switch ($channelType) {
            case 'vkontakte':
                $channelClassName::firstOrCreate([
                    'id' => $request->get('vk_group_id')
                ]);
                break;
            case 'telegram':
                list($id) = explode(':', $token = $request->get('telegram_token'));

                /** @var TelegramChannel $channel */
                $channel = $channelClassName::find($id);

                if (!$channel) {
                    $channel = new $channelClassName();
                    $channel->_access_token = $token;
                    $channel->id = $id;
                } else {
                    $channel->_access_token = $token;
                }

                $channel->save();
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
