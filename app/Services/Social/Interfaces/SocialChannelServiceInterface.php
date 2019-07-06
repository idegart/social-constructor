<?php

namespace App\Services\Social\Interfaces;

use App\Models\Social\SocialMessage;
use Illuminate\Support\Collection;

interface SocialChannelServiceInterface
{
    public function setChannelInfo() : bool ;

    public function storeMessage(Collection $requestData) : SocialMessage ;
}
