<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Social\StoreRequest;
use App\Models\Script;
use App\Models\Social\Socialable\Telegram\TelegramChannel;
use App\Models\Social\Socialable\Vkontakte\VkontakteGroup;
use App\Models\Social\SocialChannel;
use App\Http\Controllers\Controller;

class SocialChannelController extends Controller
{
    public function store(StoreRequest $request)
    {
        $channelType = $request->get('type');

        switch ($channelType) {
            case 'vkontakte':
                VkontakteGroup::updateOrCreate([
                    'id' => $request->get('vk_group_id')
                ]);
                break;
            case 'telegram':
                list($id) = explode(':', $token = $request->get('telegram_token'));

                $telegramChannel = TelegramChannel::find($id);
                if (!$telegramChannel) {
                    $telegramChannel = new TelegramChannel();

                    $telegramChannel->id = $id;
                    $telegramChannel->_access_token = $token;
                } else {
                    $telegramChannel->_access_token = $token;
                }

                $telegramChannel->save();
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
