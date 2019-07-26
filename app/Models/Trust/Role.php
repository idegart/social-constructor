<?php

namespace App\Models\Trust;

use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    protected $guarded = [];

    const SCRIPT_TEAM = 'script_team';
    const SCHEMA_TEAM = 'schema_team';

    static function getByName($name)
    {
        return self::query()->where('name', '=', $name)->first();
    }
}
