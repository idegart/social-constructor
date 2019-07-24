<?php

namespace App\Models\Social\Socialable\Chat2Desk;

use App\Models\Social\Socialable\BaseClient;

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
        return null;
    }

    public function getName(): string
    {
        return $this->name;
    }


}
