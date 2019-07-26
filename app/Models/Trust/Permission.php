<?php

namespace App\Models\Trust;

use Laratrust\Models\LaratrustPermission;

class Permission extends LaratrustPermission
{
    protected $guarded = [];

    const CREATE_BLOCK = 'create_block';

    static function getByName($name)
    {
        return self::query()->where('name', '=', $name)->first();
    }
}
