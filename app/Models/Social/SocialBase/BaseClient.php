<?php

namespace App\Models\Social\SocialBase;

use App\Models\Social\Interfaces\SocialClient\SocialClientRelationsInterface;
use App\Models\Social\SocialClient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

abstract class BaseClient extends Model implements SocialClientRelationsInterface
{
    public function socialClient(): MorphOne
    {
        return $this->morphOne(SocialClient::class, 'client');
    }

}
