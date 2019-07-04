<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function welcome()
    {
        return view('pages.site.welcome');
    }

    public function dashboard()
    {
        return view('pages.site.dashboard');
    }

    public function login()
    {
        return view('pages.site.login');
    }
}
