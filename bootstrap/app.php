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
        // Register route middleware
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'nakes' => \App\Http\Middleware\NakesMiddleware::class,
                'rekam_medis' => \App\Http\Middleware\RekamMedisMiddleware::class,
            'farmasi' => \App\Http\Middleware\FarmasiMiddleware::class,
                // Generic role-check middleware that accepts parameters, e.g. 'role:admin,perawat'
                'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
