<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Providers;

use App\Service\CarmisService;
use App\Service\CouponService;
use App\Service\EmailtplService;
use App\Service\GoodsService;
use App\Service\OrderProcessService;
use App\Service\OrderService;
use App\Service\PayService;
use Filament\Forms\Components\RichEditor;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\ServiceProvider;
use Jenssegers\Agent\Agent;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // 注册服务为单例
        $this->app->singleton('Service\GoodsService', function () {
            return $this->app->make(GoodsService::class);
        });

        $this->app->singleton('Service\PayService', function () {
            return $this->app->make(PayService::class);
        });

        $this->app->singleton('Service\CarmisService', function () {
            return $this->app->make(CarmisService::class);
        });

        $this->app->singleton('Service\OrderService', function () {
            return $this->app->make(OrderService::class);
        });

        $this->app->singleton('Service\CouponService', function () {
            return $this->app->make(CouponService::class);
        });

        $this->app->singleton('Service\OrderProcessService', function () {
            return $this->app->make(OrderProcessService::class);
        });

        $this->app->singleton('Service\EmailtplService', function () {
            return $this->app->make(EmailtplService::class);
        });

        $this->app->singleton('Jenssegers\Agent', function () {
            return $this->app->make(Agent::class);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 检查 Redis 连接（系统配置存储依赖 Redis）
        $this->checkRedisConnection();

        // 全局设置 Table 配置
        Table::configureUsing(function (Table $table): void {
            $table
                ->filtersLayout(FiltersLayout::Modal)
                ->paginationPageOptions([10, 25, 50, 100])
                ->defaultPaginationPageOption(10);
        });

        // 全局设置 Table 列的默认占位符
        TextColumn::configureUsing(function (TextColumn $column): void {
            $column->placeholder('-');
        });

        // 全局设置 TextEntry 的默认占位符
        TextEntry::configureUsing(function (TextEntry $entry): void {
            $entry->placeholder('-');
        });

        // 全局设置 RichEditor 的默认高度
        RichEditor::configureUsing(function (RichEditor $editor): void {
            $editor->extraAttributes([
                'style' => 'min-height: 400px;',
            ]);
        });
    }

    /**
     * 检查 Redis 连接
     * 系统配置使用 Cache::forever() 存储，必须使用 Redis
     */
    protected function checkRedisConnection(): void
    {
        // 跳过控制台命令（除了 serve 和 queue 命令）
        if ($this->app->runningInConsole() && ! $this->app->runningUnitTests()) {
            return;
        }

        try {
            // 检查缓存驱动是否为 Redis
            $cacheDriver = config('cache.default');
            if ($cacheDriver !== 'redis') {
                Log::warning('Cache driver is not Redis. System configurations may be lost.', [
                    'current_driver' => $cacheDriver,
                    'required_driver' => 'redis',
                ]);
            }

            // 尝试连接 Redis
            Redis::connection()->ping();

            // 测试缓存读写
            Cache::put('redis_health_check', true, 10);
            if (! Cache::get('redis_health_check')) {
                throw new \RuntimeException('Redis cache read/write test failed');
            }
        } catch (\Throwable $e) {
            $message = 'Redis connection failed. This application requires Redis for system configuration storage. '
                .'Please ensure Redis is running and properly configured in .env file.';

            Log::error($message, [
                'error' => $e->getMessage(),
                'cache_driver' => config('cache.default'),
            ]);

            // 在非测试环境中抛出异常
            if (! $this->app->runningUnitTests()) {
                throw new \RuntimeException($message, 0, $e);
            }
        }
    }
}
