<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Goods;
use App\Models\Order;
use App\Models\Pay;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('order_sn')
                    ->label('订单号')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->limit(20),

                TextColumn::make('title')
                    ->label('订单标题')
                    ->limit(30)
                    ->searchable(),

                TextColumn::make('type')
                    ->label('类型')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        Order::AUTOMATIC_DELIVERY => 'success',
                        Order::MANUAL_PROCESSING => 'info',
                    })
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        Order::AUTOMATIC_DELIVERY => '自动发货',
                        Order::MANUAL_PROCESSING => '人工处理',
                    }),

                TextColumn::make('email')
                    ->label('邮箱')
                    ->searchable()
                    ->copyable()
                    ->limit(25),

                TextColumn::make('goods.gd_name')
                    ->label('商品')
                    ->limit(20)
                    ->toggleable(),

                TextColumn::make('buy_amount')
                    ->label('数量')
                    ->alignCenter(),

                TextColumn::make('actual_price')
                    ->label('实付金额')
                    ->money('CNY')
                    ->sortable(),

                TextColumn::make('pay.pay_name')
                    ->label('支付方式')
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('状态')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        Order::STATUS_PENDING => 'warning',
                        Order::STATUS_PROCESSING => 'info',
                        Order::STATUS_COMPLETED => 'success',
                        Order::STATUS_FAILURE => 'danger',
                        Order::STATUS_ABNORMAL => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        Order::STATUS_PENDING => '待支付',
                        Order::STATUS_PROCESSING => '处理中',
                        Order::STATUS_COMPLETED => '已完成',
                        Order::STATUS_FAILURE => '失败',
                        Order::STATUS_ABNORMAL => '异常',
                        default => '未知',
                    }),

                TextColumn::make('trade_no')
                    ->label('交易流水号')
                    ->searchable()
                    ->copyable()
                    ->toggleable()
                    ->limit(20),

                TextColumn::make('search_pwd')
                    ->label('查询密码')
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('buy_ip')
                    ->label('购买IP')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('创建时间')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('更新时间')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),

                SelectFilter::make('status')
                    ->label('订单状态')
                    ->options([
                        Order::STATUS_PENDING => '待支付',
                        Order::STATUS_PROCESSING => '处理中',
                        Order::STATUS_COMPLETED => '已完成',
                        Order::STATUS_FAILURE => '失败',
                        Order::STATUS_ABNORMAL => '异常',
                    ]),

                SelectFilter::make('type')
                    ->label('订单类型')
                    ->options([
                        Order::AUTOMATIC_DELIVERY => '自动发货',
                        Order::MANUAL_PROCESSING => '人工处理',
                    ]),

                SelectFilter::make('goods_id')
                    ->label('商品')
                    ->options(Goods::query()->pluck('gd_name', 'id'))
                    ->searchable(),

                SelectFilter::make('pay_id')
                    ->label('支付方式')
                    ->options(Pay::query()->pluck('pay_name', 'id')),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }
}
