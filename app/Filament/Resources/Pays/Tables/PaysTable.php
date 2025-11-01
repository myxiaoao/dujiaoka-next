<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Filament\Resources\Pays\Tables;

use App\Models\Pay;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PaysTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('pay_name')
                    ->label('支付名称')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('pay_check')
                    ->label('标识')
                    ->searchable()
                    ->copyable()
                    ->limit(20)
                    ->badge()
                    ->color('primary')
                    ->fontFamily('mono'),

                IconColumn::make('is_open')
                    ->label('状态')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                TextColumn::make('pay_method')
                    ->label('支付类型')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        Pay::METHOD_JUMP => 'success',
                        Pay::METHOD_SCAN => 'info',
                    })
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        Pay::METHOD_JUMP => '跳转',
                        Pay::METHOD_SCAN => '扫码',
                        default => '未知',
                    }),

                TextColumn::make('pay_client')
                    ->label('适用端')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        Pay::PAY_CLIENT_PC => 'info',
                        Pay::PAY_CLIENT_MOBILE => 'warning',
                        Pay::PAY_CLIENT_ALL => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        Pay::PAY_CLIENT_PC => 'PC',
                        Pay::PAY_CLIENT_MOBILE => '移动',
                        Pay::PAY_CLIENT_ALL => '通用',
                        default => '未知',
                    }),

                TextColumn::make('merchant_id')
                    ->label('商户ID')
                    ->limit(20)
                    ->toggleable()
                    ->fontFamily('mono'),

                TextColumn::make('pay_handleroute')
                    ->label('处理路由')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->fontFamily('mono'),

                TextColumn::make('created_at')
                    ->label('创建时间')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('更新时间')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),

                SelectFilter::make('pay_method')
                    ->label('支付类型')
                    ->options([
                        Pay::METHOD_JUMP => '跳转',
                        Pay::METHOD_SCAN => '扫码',
                    ]),

                SelectFilter::make('pay_client')
                    ->label('适用端')
                    ->options([
                        Pay::PAY_CLIENT_PC => 'PC',
                        Pay::PAY_CLIENT_MOBILE => '移动端',
                    ]),

                SelectFilter::make('is_open')
                    ->label('启用状态')
                    ->options([
                        1 => '已启用',
                        0 => '已禁用',
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
