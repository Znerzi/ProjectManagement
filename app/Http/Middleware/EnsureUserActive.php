<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserActive
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && !auth()->user()->isActive()) {
            auth()->logout();
            return redirect('/login')->with('error', 'Your account is inactive.');
        }

        return $next($request);
    }
}
