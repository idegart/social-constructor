<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Script extends Model
{
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        self::creating(function (Script $script) {
            $script->user_id = Auth::id();
        });

        self::created(function (Script $script) {
            $schema = $script->schemas()->create();

            $script->starter_schema_id = $schema->getKey();
            $script->save(['timestamps' => false]);
        });
    }

    public function schemas()
    {
        return $this->hasMany(Schema::class);
    }

    public function starterSchema()
    {
        return $this->belongsTo(Schema::class, 'starter_schema_id');
    }
}
