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
    ->withMiddleware(function (Middleware $middleware): void {
<<<<<<< HEAD
        //
=======
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
>>>>>>> 7f6167b (Temel)
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
