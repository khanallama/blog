<?php

use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Middleware\LogActivity;
use App\Http\Middleware\RedirectBasedOnAuth;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\UserAuthentication;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withProviders([
        App\Providers\BusinessServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.user' => UserAuthentication::class,
            'log.activity' => LogActivity::class,
            'role' => EnsureUserHasRole::class,
            'guest' => RedirectIfAuthenticated::class,
            'redirect.auth' => RedirectBasedOnAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
