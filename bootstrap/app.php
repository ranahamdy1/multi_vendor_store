<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // الميدل ويرات العامة
        $middleware->append([
            \App\Http\Middleware\MarkNotificationAsRead::class,
        ]);

        // الأسماء المختصرة للميدل ويرات
        $middleware->alias([
            'auth.type' => \App\Http\Middleware\CheckUserType::class,
            'lastActive' => \App\Http\Middleware\UpdateUserLastActiveAt::class,
        ]);
    })
    ->withProviders([
        App\Providers\FortifyServiceProvider::class,
    ])
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
