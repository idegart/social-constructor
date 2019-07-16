<?php

use App\Models\Block\{Params\AddParam,
    Params\CompareParam,
    Params\ResetParam,
    Params\SetParam,
    ReceiveMessage,
    SendMessage,
    SendMessageWithInput,
    SendMessageWithKeyboard};

return [
    'types' => [
        'receive_message'   => ReceiveMessage::class,
        'send_message'      => SendMessage::class,

        'send_message_with_keyboard'    => SendMessageWithKeyboard::class,
        'send_message_with_input'       => SendMessageWithInput::class,

        'param_add'         => AddParam::class,
        'param_compare'     => CompareParam::class,
        'param_set'         => SetParam::class,
        'param_reset'       => ResetParam::class,
    ],
];
