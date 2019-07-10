<?php

return [
    'types' => [
        'receive_message' => \App\Models\Block\ReceiveMessage::class,
        'send_message' => \App\Models\Block\SendMessage::class,
        'send_message_with_keyboard' => \App\Models\Block\SendMessageWithKeyboard::class,
        'exit_chat' => ''
    ],
];
