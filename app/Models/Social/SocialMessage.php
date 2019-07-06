<?php

namespace App\Models\Social;

use Illuminate\Database\Eloquent\Model;

class SocialMessage extends Model
{
    protected $guarded = [];

    public function message()
    {
        return $this->morphTo();
    }
}
