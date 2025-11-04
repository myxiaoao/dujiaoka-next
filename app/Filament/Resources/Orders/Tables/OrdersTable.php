<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Coupon;
use App\Models\Goods;
use App\Models\Order;
use App\Models\Pay;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('order_sn')
                    ->label('订单号')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold')
                    ->limit(20),

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
                    })
                    ->sortable(),

                TextColumn::make('goods.gd_name')
                    ->label('商品')
                    ->limit(25)
                    ->sortable()
                    ->searchable(),

                TextColumn::make('type')
                    ->label('类型')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        Order::AUTOMATIC_DELIVERY => 'success',
                        Order::MANUAL_PROCESSING => 'warning',
                    })
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        Order::AUTOMATIC_DELIVERY => '自动',
                        Order::MANUAL_PROCESSING => '人工',
                    })
                    ->toggleable(),

                TextColumn::make('buy_amount')
                    ->label('数量')
                    ->alignCenter()
                    ->badge()
                    ->color('info')
                    ->summarize([
                        Sum::make()
                            ->label('总计'),
                    ]),

                TextColumn::make('actual_price')
                    ->label('实付金额')
                    ->money('CNY')
                    ->sortable()
                    ->weight('bold')
                    ->color('success')
                    ->summarize([
                        Sum::make()
                            ->money('CNY')
                            ->label('合计'),
                    ]),

                TextColumn::make('pay.pay_name')
                    ->label('支付方式')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('email')
                    ->label('邮箱')
                    ->searchable()
                    ->copyable()
                    ->limit(25)
                    ->toggleable(),

                TextColumn::make('search_pwd')
                    ->label('查询密码')
                    ->copyable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('下单时间')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),

                TextColumn::make('title')
                    ->label('订单标题')
                    ->limit(30)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('goods_price')
                    ->label('单价')
                    ->money('CNY')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->summarize([
                        Sum::make()
                            ->money('CNY')
                            ->label('合计'),
                    ]),

                TextColumn::make('total_price')
                    ->label('总价')
                    ->money('CNY')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->summarize([
                        Sum::make()
                            ->money('CNY')
                            ->label('合计'),
                    ]),

                TextColumn::make('coupon.coupon')
                    ->label('优惠券')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('-'),

                TextColumn::make('coupon_discount_price')
                    ->label('券折扣')
                    ->money('CNY')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->summarize([
                        Sum::make()
                            ->money('CNY')
                            ->label('合计'),
                    ]),

                TextColumn::make('wholesale_discount_price')
                    ->label('批发折扣')
                    ->money('CNY')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->summarize([
                        Sum::make()
                            ->money('CNY')
                            ->label('合计'),
                    ]),

                TextColumn::make('trade_no')
                    ->label('交易流水号')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->limit(20),

                TextColumn::make('buy_ip')
                    ->label('购买IP')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('更新时间')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
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

                SelectFilter::make('coupon_id')
                    ->label('优惠券')
                    ->options(Coupon::query()->pluck('coupon', 'id'))
                    ->searchable(),

                Filter::make('created_at')
                    ->label('创建日期范围')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('created_from')
                            ->label('开始日期'),
                        \Filament\Forms\Components\DatePicker::make('created_until')
                            ->label('结束日期'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),

                TrashedFilter::make(),
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
            ->groups([
                Group::make('status')
                    ->label('订单状态')
                    ->collapsible()
                    ->getTitleFromRecordUsing(fn (Order $record): string => match ($record->status) {
                        Order::STATUS_WAIT_PAY => '待支付',
                        Order::STATUS_PENDING => '待处理',
                        Order::STATUS_PROCESSING => '处理中',
                        Order::STATUS_COMPLETED => '已完成',
                        Order::STATUS_FAILURE => '失败',
                        Order::STATUS_ABNORMAL => '异常',
                        Order::STATUS_EXPIRED => '已过期',
                        default => '未知',
                    }),

                Group::make('goods.gd_name')
                    ->label('商品')
                    ->collapsible(),

                Group::make('pay.pay_name')
                    ->label('支付方式')
                    ->collapsible(),

                Group::make('type')
                    ->label('订单类型')
                    ->collapsible()
                    ->getTitleFromRecordUsing(fn (Order $record): string => match ($record->type) {
                        Order::AUTOMATIC_DELIVERY => '自动发货',
                        Order::MANUAL_PROCESSING => '人工处理',
                        default => '未知',
                    }),

                Group::make('created_at')
                    ->label('下单日期')
                    ->date()
                    ->collapsible(),
            ])
            ->defaultSort('id', 'desc');
    }
}
