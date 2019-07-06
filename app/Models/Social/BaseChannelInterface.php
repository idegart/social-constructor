<?php

namespace App\Models\Social;

use Illuminate\Http\Request;

interface BaseChannelInterface
{
    public function init(Request $request) : void ;

    public function getChannelPhoto() : string ;

    public function getChannelName() : string ;

    public function getChannelLink() : string ;

    public function getCallbackUrl() : string ;

    public function setChannelInfo() : bool ;

    public function hasAccessToken() : bool ;

    public function getAccessLink() : string ;

    public static function handleAccessCallback(Request $request);
}
