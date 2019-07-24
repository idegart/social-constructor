<?php

namespace App\Models\Block\ExternalApi;

use App\Models\Block;
use App\Models\Block\ExternalApi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ExternalApiOption extends Model
{
    protected $table = 'external_api_block_options';

    protected $guarded = [];

    public function externalApi()
    {
        return $this->belongsTo(ExternalApi::class, 'external_api_blocks_id');
    }

    public function nextBlock()
    {
        return $this->belongsTo(Block::class, 'next_block_id');
    }

    public function setKeyAttribute($value)
    {
        $this->attributes['key'] = Str::upper($value);
    }
}
