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

// REGISTERED MIDDLEWARE:
// USERTYPE = PREVENT ROUTING TO UNAUTHORIZED PAGE

    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'user'=> \App\Http\Middleware\userType::class,
            'admin'=> \App\Http\Middleware\RegisterAdmin::class,
            'sesh'=> \App\Http\Middleware\TrackSession::class,
            'demo_user'=> \App\Http\Middleware\DemoUser::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

    })->create();
