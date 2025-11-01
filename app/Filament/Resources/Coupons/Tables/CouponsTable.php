<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Filament\Resources\Coupons\Tables;

use App\Models\Coupon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CouponsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('coupon')
                    ->label('优惠券码')
                    ->searchable()
                    ->copyable()
                    ->weight('bold')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('discount')
                    ->label('优惠金额')
                    ->money('CNY')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),

                TextColumn::make('ret')
                    ->label('剩余次数')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state == 0 => 'info',
                        $state > 10 => 'success',
                        $state > 0 => 'warning',
                        default => 'danger',
                    })
                    ->formatStateUsing(fn (int $state): string => $state == 0 ? '无限' : (string) $state),

                TextColumn::make('is_use')
                    ->label('使用状态')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        Coupon::STATUS_UNUSED => 'success',
                        Coupon::STATUS_USE => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        Coupon::STATUS_UNUSED => '可用',
                        Coupon::STATUS_USE => '已用',
                        default => '未知',
                    }),

                IconColumn::make('is_open')
                    ->label('启用')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('created_at')
                    ->label('创建时间')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('更新时间')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),

                SelectFilter::make('is_use')
                    ->label('使用状态')
                    ->options([
                        Coupon::STATUS_UNUSED => '未使用',
                        Coupon::STATUS_USE => '已使用',
                    ]),

                SelectFilter::make('is_open')
                    ->label('启用状态')
                    ->options([
                        1 => '启用',
                        0 => '禁用',
                    ]),

                SelectFilter::make('goods_id')
                    ->label('关联商品')
                    ->relationship('goods', 'gd_name')
                    ->searchable()
                    ->preload(),
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
