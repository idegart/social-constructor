<?php

namespace App\Models\Social\Socialable\Chat2Desk;

use App\Models\Social\Socialable\BaseClient;
use App\Services\PlayService;

class Chat2DeskUser extends BaseClient
{
    protected $guarded = [];

    protected $table = 'chat_2_desk_users';

    public function getRealId(): string
    {
        return $this->id;
    }

    public function getSocialType(): string
    {
        $phone = explode(' ', $this->phone);

        switch ($phone[0]) {
            case '[vk]':
                return PlayService::VKONTAKTE;
            case '[tg]':
                return PlayService::TELEGRAM;
            default:
                return PlayService::VKONTAKTE;
        }
    }

    public function getName(): string
    {
        return $this->name;
    }


}
