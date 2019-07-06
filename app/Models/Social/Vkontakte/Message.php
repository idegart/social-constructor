<?php

namespace App\Models\Social\Vkontakte;

use App\Models\Social\SocialBase\BaseMessage;

class Message extends BaseMessage
{
    protected $table = 'vkontakte_messages';

    protected $guarded = [];
}
