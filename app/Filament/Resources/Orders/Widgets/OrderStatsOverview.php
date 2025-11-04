<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Filament\Resources\Orders\Widgets;

use App\Models\Order;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        // 总订单数
        $totalOrders = Order::count();

        // 未完成订单数（待支付、待处理、处理中）
        $openOrders = Order::whereIn('status', [
            Order::STATUS_WAIT_PAY,
            Order::STATUS_PENDING,
            Order::STATUS_PROCESSING,
        ])->count();

        // 已完成订单的平均金额
        $averagePrice = Order::where('status', Order::STATUS_COMPLETED)
            ->avg('actual_price') ?? 0;

        // 获取最近7天的订单趋势数据
        $recentOrders = Order::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count')
            ->toArray();

        // 填充缺失的天数数据
        $chartData = array_pad($recentOrders, 7, 0);
        $chartData = array_slice($chartData, -7);

        return [
            Stat::make('订单总数', number_format((int) $totalOrders))
                ->description('所有订单')
                ->descriptionIcon('heroicon-o-shopping-cart', IconPosition::Before)
                ->color('primary')
                ->chart($chartData),

            Stat::make('未完成订单', number_format((int) $openOrders))
                ->description('待支付、待处理、处理中')
                ->descriptionIcon('heroicon-o-clock', IconPosition::Before)
                ->color('warning'),

            Stat::make('平均订单金额', '¥'.number_format((float) $averagePrice, 2))
                ->description('已完成订单的平均金额')
                ->descriptionIcon('heroicon-o-currency-dollar', IconPosition::Before)
                ->color('success'),
        ];
    }
}
