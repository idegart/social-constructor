<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Schema extends Model
{
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        self::creating(function (Schema $schema) {
            $schema->title = Str::random();
        });
    }

    public function script()
    {
        return $this->belongsTo(Script::class);
    }

    public function blocks()
    {
        return $this->hasMany(Block::class);
    }
}
