<?php

namespace App\Filament\Resources\Coupons\Tables;

use App\Models\Coupon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
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
                    ->sortable(),

                TextColumn::make('coupon')
                    ->label('优惠券码')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('type')
                    ->label('类型')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        Coupon::TYPE_FIXED_AMOUNT => 'success',
                        Coupon::TYPE_PERCENTAGE => 'info',
                    })
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        Coupon::TYPE_FIXED_AMOUNT => '固定金额',
                        Coupon::TYPE_PERCENTAGE => '百分比',
                    }),

                TextColumn::make('discount')
                    ->label('折扣值'),

                TextColumn::make('used')
                    ->label('已使用')
                    ->sortable()
                    ->badge()
                    ->color('warning'),

                TextColumn::make('ret')
                    ->label('剩余次数')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'success' : 'danger')
                    ->formatStateUsing(fn (int $state): string => $state == 0 ? '无限' : (string)$state),

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

                SelectFilter::make('type')
                    ->label('优惠券类型')
                    ->options([
                        Coupon::TYPE_FIXED_AMOUNT => '固定金额',
                        Coupon::TYPE_PERCENTAGE => '百分比',
                    ]),
            ])
            ->recordActions([
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
