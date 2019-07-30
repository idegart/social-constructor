<?php

namespace App\Models;

use App\Models\Script\ScriptExternalApi;
use App\Models\Script\ScriptVariable;
use App\Models\Trust\Role;
use App\Models\Trust\Team;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

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

            /** @var Team $team */
            $team = Team::create([
                'name' => $script->getTeamName(),
                'display_name' => $script->getTeamFrontName(),
            ]);

            Auth::user()->attachRole(Role::getByName(Role::SCRIPT_TEAM), $team);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schemas()
    {
        return $this->hasMany(Schema::class);
    }

    public function starterSchema()
    {
        return $this->belongsTo(Schema::class, 'starter_schema_id');
    }

    public function variables()
    {
        return $this->hasMany(ScriptVariable::class);
    }

    public function externalApi()
    {
        return $this->hasMany(ScriptExternalApi::class);
    }

    public function team() : ?Team
    {
        return Team::query()->where('name', '=', $this->getTeamName())->first();
    }

    public function teamUsers()
    {
        return User::whereRoleIs(Role::SCRIPT_TEAM, $this->team())
            ->with([
                'roles' => function (MorphToMany $query) {
                    $query->wherePivot('team_id', $this->team()->id);
                }
            ]);
    }

    public function getTeamName()
    {
        return Team::SCRIPT . '_' . $this->id;
    }

    public function getTeamFrontName() : string
    {
        return 'Script team: ' . $this->title;
    }
}
