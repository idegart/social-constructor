<?php

namespace App\Services\Social;

class SocialKeyboard
{
    public $buttons;

    public function __construct()
    {
        $this->buttons = collect();
    }

    public function addButton(SocialKeyboardButton $button)
    {
        $this->buttons->push($button);
    }
}
