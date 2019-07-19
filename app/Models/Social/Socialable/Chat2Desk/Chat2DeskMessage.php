<?php

namespace App\Models\Social\Socialable\Chat2Desk;

use App\Models\Social\Socialable\BaseMessage;

class Chat2DeskMessage extends BaseMessage
{
    protected $table = 'chat_2_desk_messages';

    protected $guarded = [];

    public function getText()
    {
        return $this->text;
    }

    public function isOut(): bool
    {
        return $this->type == 'to_client';
    }

}
