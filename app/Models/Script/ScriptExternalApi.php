<?php

namespace App\Models\Script;

use App\Models\Script;
use Illuminate\Database\Eloquent\Model;

class ScriptExternalApi extends Model
{
    protected $table = 'script_external_api';

    protected $fillable = [
        'title', 'url',
        'auth_login', 'auth_password',
    ];

    public function script()
    {
        return $this->belongsTo(Script::class);
    }
}
