<?php

namespace App\Models\Script;

use App\Models\Script;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ScriptExternalApi extends Model
{
    protected $table = 'script_external_api';

    protected $fillable = [
        'title', 'url',
        'auth_login', 'auth_password',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (ScriptExternalApi $externalApi) {
            $externalApi->secret = Str::random();
        });
    }

    public function script()
    {
        return $this->belongsTo(Script::class);
    }
}
