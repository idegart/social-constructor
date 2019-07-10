<?php

namespace App\Models\Social;

use App\Models\Block;
use App\Models\Schema;
use App\Models\Script;
use Illuminate\Database\Eloquent\Model;

class SocialChat extends Model
{
    protected $guarded = [];

    public function socialMessages()
    {
        return $this->hasMany(SocialMessage::class);
    }

    public function socialClient()
    {
        return $this->hasMany(SocialClient::class);
    }

    public function currentScript()
    {
        return $this->belongsTo(Script::class, 'current_script_id');
    }

    public function currentSchema()
    {
        return $this->belongsTo(Schema::class, 'current_schema_id');
    }

    public function currentBlock()
    {
        return $this->belongsTo(Block::class, 'current_block_id');
    }
}
