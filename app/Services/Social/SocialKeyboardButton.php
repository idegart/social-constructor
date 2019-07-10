<?php

namespace App\Services\Social;

class SocialKeyboardButton
{
    public $type;

    public $label;

    public function __construct($label, $type = 'text')
    {
        $this->label = $label;

        $this->type = $type;
    }
}
