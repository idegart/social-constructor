<?php

namespace App\Models\Trust;

use Laratrust\Models\LaratrustTeam;

class Team extends LaratrustTeam
{
    protected $guarded = [];

    const SCRIPT = 'team_script';
    const SCHEMA = 'team_schema';

    static function getByName($name)
    {
        return self::query()->where('name', '=', $name)->first();
    }
}
