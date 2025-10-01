<?php

use App\Http\Middleware\CheckCasse;
use App\Http\Middleware\CheckClient;
use App\Http\Middleware\EnsureUserHasRole;
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
        $middleware->alias([
//            'casse' => CheckCasse::class,
//          //  'web' => \App\Http\Middleware\ShareCasseData::class,
//            'client' => CheckClient::class,
            'role' => EnsureUserHasRole::class,

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
