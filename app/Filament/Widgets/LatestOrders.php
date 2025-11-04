<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Filament\Widgets;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestOrders extends TableWidget
{
    protected static ?string $heading = '最近订单';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->with(['goods', 'pay'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('created_at')
                    ->label('订单日期')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),

                TextColumn::make('order_sn')
                    ->label('订单号')
                    ->searchable()
                    ->copyable()
                    ->weight('bold')
                    ->limit(20),

                TextColumn::make('email')
                    ->label('客户邮箱')
                    ->searchable()
                    ->copyable()
                    ->limit(25),

                TextColumn::make('status')
                    ->label('状态')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        Order::STATUS_WAIT_PAY => 'gray',
                        Order::STATUS_PENDING => 'warning',
                        Order::STATUS_PROCESSING => 'info',
                        Order::STATUS_COMPLETED => 'success',
                        Order::STATUS_FAILURE => 'danger',
                        Order::STATUS_ABNORMAL => 'danger',
                        Order::STATUS_EXPIRED => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        Order::STATUS_WAIT_PAY => '待支付',
                        Order::STATUS_PENDING => '待处理',
                        Order::STATUS_PROCESSING => '处理中',
                        Order::STATUS_COMPLETED => '已完成',
                        Order::STATUS_FAILURE => '失败',
                        Order::STATUS_ABNORMAL => '异常',
                        Order::STATUS_EXPIRED => '已过期',
                        default => '未知',
                    }),

                TextColumn::make('goods.gd_name')
                    ->label('商品')
                    ->limit(25)
                    ->searchable(),

                TextColumn::make('pay.pay_name')
                    ->label('支付方式')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('actual_price')
                    ->label('实付金额')
                    ->money('CNY')
                    ->weight('bold')
                    ->color('success'),
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn (Order $record): string => OrderResource::getUrl('edit', ['record' => $record])),
            ])
            ->paginated(false);
    }
}
