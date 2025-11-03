<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // 自定义路由绑定：支持通过 ID 或 order_sn 查找订单
            Route::bind('order', function (string $value) {
                // 如果是纯数字，尝试通过 ID 查找
                if (is_numeric($value)) {
                    return \App\Models\Order::findOrFail($value);
                }

                // 否则通过 order_sn 查找
                return \App\Models\Order::where('order_sn', $value)->firstOrFail();
            });
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'dujiaoka.boot' => \App\Http\Middleware\DujiaoBoot::class,
            'dujiaoka.system' => \App\Http\Middleware\DujiaoSystem::class,
            'dujiaoka.pay_gate_way' => \App\Http\Middleware\PayGateWay::class,
        ]);
    })
    ->withProviders([
        \App\Providers\EventServiceProvider::class,
    ])
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
