<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Filament\Widgets;

use App\Models\Goods;
use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        // 今日订单统计
        $todayOrders = Order::whereDate('created_at', Carbon::today())->count();
        $todayCompletedOrders = Order::whereDate('created_at', Carbon::today())
            ->where('status', Order::STATUS_COMPLETED)
            ->count();

        // 今日销售额
        $todaySales = Order::whereDate('created_at', Carbon::today())
            ->where('status', Order::STATUS_COMPLETED)
            ->sum('actual_price');

        // 总订单数
        $totalOrders = Order::count();

        // 总销售额
        $totalSales = Order::where('status', Order::STATUS_COMPLETED)
            ->sum('actual_price');

        // 商品总数
        $totalGoods = Goods::count();

        // 成功率（已完成订单数 / 总订单数）
        $successRate = $totalOrders > 0
            ? round(($todayCompletedOrders / max($todayOrders, 1)) * 100, 2)
            : 0;

        return [
            Stat::make('今日订单', $todayOrders)
                ->description('今日订单总数')
                ->descriptionIcon('heroicon-o-shopping-cart')
                ->color('primary')
                ->chart([7, 12, 18, 15, 20, 25, $todayOrders]),

            Stat::make('今日成功订单', $todayCompletedOrders)
                ->description('已完成的订单')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->chart([5, 10, 15, 12, 18, 20, $todayCompletedOrders]),

            Stat::make('今日销售额', '¥'.number_format($todaySales, 2))
                ->description('今日完成订单总金额')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make('总销售额', '¥'.number_format($totalSales, 2))
                ->description('累计销售总额')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('info'),

            Stat::make('总订单数', number_format($totalOrders))
                ->description('累计订单总数')
                ->descriptionIcon('heroicon-o-clipboard-document-list')
                ->color('warning'),

            Stat::make('今日成功率', $successRate.'%')
                ->description('今日订单完成率')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color($successRate > 80 ? 'success' : ($successRate > 50 ? 'warning' : 'danger')),
        ];
    }
}
