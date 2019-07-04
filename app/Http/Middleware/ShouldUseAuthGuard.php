<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class ShouldUseAuthGuard
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $guard
     * @return mixed
     */
    public function handle($request, Closure $next, string $guard)
    {
        Auth::shouldUse($guard);

        return $next($request);
    }
}
