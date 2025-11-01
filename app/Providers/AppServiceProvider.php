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
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
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
    }
}
