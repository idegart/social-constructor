<?php

namespace App\Models\Script;

use App\Models\Script;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ScriptVariable extends Model
{
    const TYPE_STRING = 'string';
    const TYPE_INTEGER = 'integer';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_TIME = 'time';
    const TYPE_DATE = 'date';
    const TYPE_DATETIME = 'datetime';

    const TYPES = [
        self::TYPE_STRING,
        self::TYPE_INTEGER,
        self::TYPE_BOOLEAN,
        self::TYPE_TIME, self::TYPE_DATE, self::TYPE_DATETIME,
    ];

    protected $guarded = [];

    public function script()
    {
        return $this->belongsTo(Script::class);
    }

    public function setVariableAttribute($value)
    {
        $this->attributes['variable'] = Str::upper($value);
    }

    public function getDefaultAttribute()
    {
        switch ($this->type) {
            case ScriptVariable::TYPE_STRING:   return $this->default_string;
            case ScriptVariable::TYPE_INTEGER:  return $this->default_integer;
            case ScriptVariable::TYPE_BOOLEAN:  return $this->default_boolean;
            case ScriptVariable::TYPE_DATE:     return $this->default_date;
            case ScriptVariable::TYPE_TIME:     return $this->default_time;
            case ScriptVariable::TYPE_DATETIME: return $this->default_datetime;
            default:                            return '';
        }
    }
}
