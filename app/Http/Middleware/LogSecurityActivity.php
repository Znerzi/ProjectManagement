<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LogSecurityActivity
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            auth()->user()->update([
                'last_login_ip' => $request->ip(),
            ]);
        }

        return $next($request);
    }
}
