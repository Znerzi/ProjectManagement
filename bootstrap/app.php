<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureAdminRole::class,
            'client' => \App\Http\Middleware\EnsureClientRole::class,
            'developer' => \App\Http\Middleware\EnsureDeveloperRole::class,
            'active' => \App\Http\Middleware\EnsureUserActive::class,
            'security' => \App\Http\Middleware\LogSecurityActivity::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
