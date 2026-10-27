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
            'admin'        => \App\Http\Middleware\AdminMiddleware::class,
            'student'      => \App\Http\Middleware\StudentMiddleware::class,
            'subscription' => \App\Http\Middleware\RequireSubscription::class,
        ]);

        // When an authenticated user hits a 'guest' route (like /register or /login),
        // redirect based on their role. Use plain paths instead of route() helper
        // since route names may not be fully resolved at this middleware stage.
        $middleware->redirectUsersTo(function (\Illuminate\Http\Request $request) {
            if (auth()->check() && auth()->user()->isAdmin()) {
                return '/dashboard';
            }
            return '/portal';
        });

        // Keep the admin maintenance screen reachable while the site is down,
        // otherwise nobody could ever switch maintenance mode back off.
        $middleware->preventRequestsDuringMaintenance(except: [
            'maintenance',
            'maintenance/*',
        ]);

        // Scoped to the 'web' group — SetLocale reads from session so must run after StartSession
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\SecurityHeaders::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();