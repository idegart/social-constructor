<?php

namespace App\Models\Social\Socialable;

use App\Models\Social\SocialClient;
use Illuminate\Database\Eloquent\Model;

abstract class BaseClient extends Model
{
    protected $guarded = [];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::created(function (BaseClient $client) {
            $client->socialClient()->create();
        });
    }

    public function socialClient()
    {
        return $this->morphOne(SocialClient::class, 'client');
    }

    abstract public function getRealId() : string ;
    abstract public function getSocialType() : string ;
    abstract public function getName() : string ;
}
