<?php

namespace App\Models\Social\Interfaces\SocialClient;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

interface SocialClientRelationsInterface
{
    public function socialClient() : MorphOne;
}
