<?php

namespace App\Http\Controllers;

use App\Http\Requests\Script\AddUserToTeamRequest;
use App\Http\Requests\Script\RemoveUserFromTeamRequest;
use App\Models\Script;
use App\Models\Trust\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ScriptController extends Controller
{
    public function show(Script $script)
    {
        $team = $script->teamUsers()->get();

        return view('pages.script.show', compact('script', 'team'));
    }

    public function editor(Script $script)
    {
        return view('pages.script.editor', compact('script'));
    }

    public function addUserToTeam(AddUserToTeamRequest $request, Script $script)
    {
        $user = User::query()->where('email', $request->get('email'))->first();

        $user->attachRole(Role::SCRIPT_TEAM, $script->team());

        return redirect()->back(Response::HTTP_CREATED);
    }

    public function removeUserFromTeam(RemoveUserFromTeamRequest $request, Script $script)
    {
        $user = User::query()->where('email', $request->get('email'))->first();

        $user->detachRole(Role::SCRIPT_TEAM, $script->team());

        return redirect()->back(Response::HTTP_CREATED);
    }
}
