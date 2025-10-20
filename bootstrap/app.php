<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            /*'admin' => App\Http\Middleware\AdminMiddleware::class,
            'capturista' => App\Http\Middleware\CapturistaMiddleware::class,
            'profesor' => App\Http\Middleware\ProfesorMiddleware::class,
            'alumno' => App\Http\Middleware\AlumnoMiddleware::class,*/
            'rol' => \App\Http\Middleware\CheckRol::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
