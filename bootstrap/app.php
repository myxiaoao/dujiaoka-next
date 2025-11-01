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
        $middleware->alias([
            'dujiaoka.boot' => \App\Http\Middleware\DujiaoBoot::class,
            'dujiaoka.system' => \App\Http\Middleware\DujiaoSystem::class,
            'install.check' => \App\Http\Middleware\InstallCheck::class,
            'dujiaoka.pay_gate_way' => \App\Http\Middleware\PayGateWay::class,
        ]);
    })
    ->withProviders([
        \App\Providers\EventServiceProvider::class,
    ])
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
