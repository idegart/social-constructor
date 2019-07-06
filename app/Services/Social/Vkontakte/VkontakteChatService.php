<?php

namespace App\Services\Social\Vkontakte;

use App\Models\Social\SocialBase\BaseChat;
use App\Models\Social\SocialBase\BaseClient;
use App\Models\Social\SocialBase\BaseMessage;
use App\Models\Social\Vkontakte\Chat;
use App\Models\Social\Vkontakte\Message;
use App\Services\Social\Interfaces\SocialChatServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use VK\Client\VKApiClient;

class VkontakteChatService implements SocialChatServiceInterface
{
    public $vkontakteChat;

    public $vkApi;

    public function __construct(Chat $vkontakteChat)
    {
        $this->vkontakteChat = $vkontakteChat;

        $this->vkApi = new VKApiClient();
    }

    public function storeMessage(BaseClient $client, Collection $messageData): BaseMessage
    {
        /** @var Message $message */
        $message = $this->vkontakteChat->messages()->create([
            'id' => $messageData->get('id'),
            'from_id' => $messageData->get('from_id'),
            'peer_id' => $messageData->get('peer_id'),
            'text' => $messageData->get('text'),
            'date' => Carbon::parse($messageData->get('date'))->toDateTimeString(),
            'attachments' => json_encode($messageData->get('attachments')),
            'important' => $messageData->get('important'),
            'vkontakte_client_id' => $client->getKey(),
        ]);

        return $message;
    }


}
