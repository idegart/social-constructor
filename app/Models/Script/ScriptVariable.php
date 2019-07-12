<?php

namespace App\Models\Script;

use App\Models\Script;
use Illuminate\Database\Eloquent\Model;

class ScriptVariable extends Model
{
    const TYPE_STRING = 'string';
    const TYPE_INTEGER = 'integer';
    const TYPE_BOOLEAN = 'boolean';

    const TYPES = [
        self::TYPE_STRING,
        self::TYPE_INTEGER,
        self::TYPE_BOOLEAN
    ];

    protected $guarded = [];

    public function script()
    {
        return $this->belongsTo(Script::class);
    }
}
