<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\Orders\Widgets\OrderStatsOverview;
use App\Models\Order;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            OrderStatsOverview::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('全部')
                ->badge(Order::count()),
            'wait_pay' => Tab::make('待支付')
                ->badge(Order::where('status', Order::STATUS_WAIT_PAY)->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', Order::STATUS_WAIT_PAY)),
            'pending' => Tab::make('待处理')
                ->badge(Order::where('status', Order::STATUS_PENDING)->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', Order::STATUS_PENDING)),
            'processing' => Tab::make('处理中')
                ->badge(Order::where('status', Order::STATUS_PROCESSING)->count())
                ->badgeColor('primary')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', Order::STATUS_PROCESSING)),
            'completed' => Tab::make('已完成')
                ->badge(Order::where('status', Order::STATUS_COMPLETED)->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', Order::STATUS_COMPLETED)),
            'failed' => Tab::make('失败/取消')
                ->badge(Order::whereIn('status', [Order::STATUS_FAILURE, Order::STATUS_EXPIRED, Order::STATUS_ABNORMAL])->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', [Order::STATUS_FAILURE, Order::STATUS_EXPIRED, Order::STATUS_ABNORMAL])),
        ];
    }
}
