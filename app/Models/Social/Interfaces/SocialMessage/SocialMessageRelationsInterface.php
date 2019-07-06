<?php

namespace App\Models\Social\Interfaces\SocialMessage;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

interface SocialMessageRelationsInterface
{
    public function socialMessage() : MorphOne;
}
