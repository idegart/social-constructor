<?php

namespace App\Services\Social\Vkontakte;

use App\Models\Social\Vkontakte\Client;
use App\Services\Social\Interfaces\SocialClientServiceInterface;
use Exception;
use VK\Client\VKApiClient;

class VkontakteClientService implements SocialClientServiceInterface
{
    public $vkontakteClient;

    public $vkApi;

    public function __construct(Client $vkontakteClient)
    {
        $this->vkontakteClient = $vkontakteClient;

        $this->vkApi = new VKApiClient();
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function setClientInfo(): bool
    {
        $response = $this->vkApi->users()->get(config('services.vkontakte.client_service_key'), [
            'user_ids' => [$this->vkontakteClient->id]
        ]);

        if (!$response || !isset($response[0])) {
            return false;
        }

        $clientInfo = collect($response[0])->only(['id', 'first_name', 'last_name', 'is_closed']);

        $this->vkontakteClient->setRawAttributes($clientInfo->toArray());

        return true;
    }
}
