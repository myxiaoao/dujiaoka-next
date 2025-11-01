<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Filament\Resources\Goods\Tables;

use App\Models\Carmis;
use App\Models\Goods;
use App\Models\GoodsGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class GoodsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                ImageColumn::make('picture')
                    ->label('图片')
                    ->square()
                    ->size(60),

                TextColumn::make('gd_name')
                    ->label('商品名称')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('gd_description')
                    ->label('商品描述')
                    ->limit(40)
                    ->toggleable(),

                TextColumn::make('group.gp_name')
                    ->label('商品分类')
                    ->sortable(),

                TextColumn::make('type')
                    ->label('发货类型')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        Goods::AUTOMATIC_DELIVERY => 'success',
                        Goods::MANUAL_PROCESSING => 'info',
                    })
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        Goods::AUTOMATIC_DELIVERY => '自动发货',
                        Goods::MANUAL_PROCESSING => '人工处理',
                    }),

                TextColumn::make('retail_price')
                    ->label('零售价')
                    ->money('CNY')
                    ->toggleable(),

                TextColumn::make('actual_price')
                    ->label('实际售价')
                    ->money('CNY')
                    ->sortable(),

                TextColumn::make('in_stock')
                    ->label('库存')
                    ->getStateUsing(function (Goods $record): int {
                        // 如果是自动发货，显示实际卡密库存
                        if ($record->type == Goods::AUTOMATIC_DELIVERY) {
                            return Carmis::query()
                                ->where('goods_id', $record->id)
                                ->where('status', Carmis::STATUS_UNSOLD)
                                ->count();
                        }

                        return $record->in_stock;
                    })
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'success' : 'danger'),

                TextColumn::make('sales_volume')
                    ->label('销量')
                    ->sortable(),

                TextColumn::make('ord')
                    ->label('排序')
                    ->sortable(),

                IconColumn::make('is_open')
                    ->label('状态')
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

                SelectFilter::make('type')
                    ->label('发货类型')
                    ->options([
                        Goods::AUTOMATIC_DELIVERY => '自动发货',
                        Goods::MANUAL_PROCESSING => '人工处理',
                    ]),

                SelectFilter::make('group_id')
                    ->label('商品分类')
                    ->options(GoodsGroup::query()->pluck('gp_name', 'id'))
                    ->searchable(),
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
            ->defaultSort('ord', 'desc');
    }
}
