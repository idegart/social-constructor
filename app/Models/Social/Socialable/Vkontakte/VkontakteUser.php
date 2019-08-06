<?php

namespace App\Models\Social\Socialable\Vkontakte;

use App\Models\Social\Socialable\BaseClient;
use App\Services\PlayService;
use App\Services\Social\VkontakteSocialService;
use Exception;

class VkontakteUser extends BaseClient
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function (VkontakteUser $user) {
            return $user->setClientInfo();
        });
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function setClientInfo(): bool
    {
        $service = new VkontakteSocialService();

        return $service->setClientInfo($this);
    }

    public function getRealId(): string
    {
        return $this->id;
    }

    public function getSocialType(): string
    {
        return PlayService::VKONTAKTE;
    }

    public function getName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }


}
