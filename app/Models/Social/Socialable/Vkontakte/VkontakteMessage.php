<?php

namespace App\Models\Social\Socialable\Vkontakte;

use App\Models\Social\Socialable\BaseMessage;

class VkontakteMessage extends BaseMessage
{
    public function getText()
    {
        return $this->text;
    }

    public function isOut(): bool
    {
        return $this->out;
    }

}
