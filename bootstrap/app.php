<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'announcement.manager' => \App\Http\Middleware\EnsureAnnouncementManager::class,
            'event.manager' => \App\Http\Middleware\EnsureEventManager::class,
            'admin' => \App\Http\Middleware\EnsureAdmin::class,
            'club.manager' => \App\Http\Middleware\EnsureClubManager::class,
            'club.admin' => \App\Http\Middleware\EnsureClubAdmin::class,
            'room.manager' => \App\Http\Middleware\EnsureRoomManager::class,
        ]);

        // API requests should return JSON authentication errors.
        $middleware->redirectGuestsTo(
            fn (\Illuminate\Http\Request $request) =>
                $request->is('api/*') ? null : '/'
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
