<?php

namespace App\Http\Controllers;

use App\Models\Script;
use Illuminate\Http\Request;

class ScriptController extends Controller
{
    public function show(Script $script)
    {
        return view('pages.script.show', compact('script'));
    }

    public function editor(Script $script)
    {
        return view('pages.script.editor', compact('script'));
    }
}
