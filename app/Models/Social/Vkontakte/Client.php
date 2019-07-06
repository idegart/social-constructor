<?php

namespace App\Models\Social\Vkontakte;

use App\Models\Social\SocialBase\BaseClient;
use App\Services\Social\Vkontakte\VkontakteClientService;
use Exception;

class Client extends BaseClient
{
    protected $table = 'vkontakte_clients';

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Client $client) {
            return $client->setClientInfo();
        });
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function setClientInfo() : bool
    {
        $service = new VkontakteClientService($this);

        return $service->setClientInfo();
    }

}
