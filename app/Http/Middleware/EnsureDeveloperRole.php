<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureDeveloperRole
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isDeveloper()) {
            abort(403, 'Unauthorized - Developer access required.');
        }

        return $next($request);
    }
}
