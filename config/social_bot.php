<?php

use App\Models\Block\{ExternalApi,
    Filters\SocialFilter,
    Params\AddParam,
    Params\CompareParam,
    Params\ResetParam,
    Params\SetParam,
    ReceiveMessage,
    SendMessage,
    SendMessageWithInput,
    SendMessageWithKeyboard};
use App\Services\PlayService;
use App\Services\Social\{Chat2DeskSocialService, TelegramSocialService, VkontakteSocialService};

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

        'filter_social'     => SocialFilter::class,

        'external_api'      => ExternalApi::class,
    ],
    'services' => [
        PlayService::VKONTAKTE => VkontakteSocialService::class,
        PlayService::TELEGRAM => TelegramSocialService::class,
        'chat2desk' => Chat2DeskSocialService::class,
    ],
];
