<?php

namespace App\Models\Social\Interfaces\SocialChannel;

interface SocialChannelAttributesInterface
{
    public function getChannelPhoto() : string ;

    public function getChannelName() : string ;

    public function getChannelLink() : string ;

    public function getCallbackUrl() : string ;

    public function getAccessLink() : string ;

    public function hasAccessToken() : bool ;

    public function setChannelInfo() : bool ;
}
