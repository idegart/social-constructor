<?php

namespace App\Models\Social\Interfaces\SocialChannel;

use Illuminate\Database\Eloquent\Relations\MorphOne;

interface SocialChannelRelationsInterface
{
    public function socialChannel() : MorphOne;
}
