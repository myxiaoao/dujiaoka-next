<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Filament\Widgets;

use App\Models\Goods;
use App\Models\Order;
use Carbon\Carbon;
use Filament\Support\Enums\IconPosition;
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

        // 获取过去12个月的订单趋势数据
        $monthlyOrdersChart = $this->getMonthlyOrdersChart();

        // 获取过去12个月的销售额趋势数据
        $monthlySalesChart = $this->getMonthlySalesChart();

        return [
            Stat::make('今日订单', $todayOrders)
                ->description('今日订单总数')
                ->descriptionIcon('heroicon-o-shopping-cart', IconPosition::Before)
                ->color('primary')
                ->chart($monthlyOrdersChart),

            Stat::make('今日成功订单', $todayCompletedOrders)
                ->description('已完成的订单')
                ->descriptionIcon('heroicon-o-check-circle', IconPosition::Before)
                ->color('success')
                ->chart($monthlyOrdersChart),

            Stat::make('今日销售额', '¥'.number_format((float) ($todaySales ?? 0), 2))
                ->description('今日完成订单总金额')
                ->descriptionIcon('heroicon-o-currency-dollar', IconPosition::Before)
                ->color('success')
                ->chart($monthlySalesChart),

            Stat::make('总销售额', '¥'.number_format((float) ($totalSales ?? 0), 2))
                ->description('累计销售总额')
                ->descriptionIcon('heroicon-o-banknotes', IconPosition::Before)
                ->color('info')
                ->chart($monthlySalesChart),

            Stat::make('总订单数', number_format((int) $totalOrders))
                ->description('累计订单总数')
                ->descriptionIcon('heroicon-o-clipboard-document-list', IconPosition::Before)
                ->color('warning')
                ->chart($monthlyOrdersChart),

            Stat::make('今日成功率', $successRate.'%')
                ->description('今日订单完成率')
                ->descriptionIcon('heroicon-o-chart-bar', IconPosition::Before)
                ->color($successRate > 80 ? 'success' : ($successRate > 50 ? 'warning' : 'danger')),
        ];
    }

    /**
     * 获取过去12个月的订单数量趋势
     */
    private function getMonthlyOrdersChart(): array
    {
        $data = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Order::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $data[] = $count;
        }

        return $data;
    }

    /**
     * 获取过去12个月的销售额趋势
     */
    private function getMonthlySalesChart(): array
    {
        $data = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $sales = Order::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->where('status', Order::STATUS_COMPLETED)
                ->sum('actual_price');

            // 将销售额转换为合理的图表数值（除以100或1000以便展示）
            $data[] = (int) ($sales / 100);
        }

        return $data;
    }
}
