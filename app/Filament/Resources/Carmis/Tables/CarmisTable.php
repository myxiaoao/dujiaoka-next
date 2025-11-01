<?php

namespace App\Filament\Resources\Carmis\Tables;

use App\Models\Carmis;
use App\Models\Goods;
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

class CarmisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('goods.gd_name')
                    ->label('关联商品')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('carmi')
                    ->label('卡密')
                    ->limit(30)
                    ->searchable()
                    ->copyable()
                    ->tooltip('点击复制'),

                TextColumn::make('status')
                    ->label('状态')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        Carmis::STATUS_UNSOLD => 'success',
                        Carmis::STATUS_SOLD => 'danger',
                    })
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        Carmis::STATUS_UNSOLD => '未售出',
                        Carmis::STATUS_SOLD => '已售出',
                    }),

                IconColumn::make('is_loop')
                    ->label('循环使用')
                    ->boolean()
                    ->trueIcon('heroicon-o-arrow-path')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('info')
                    ->falseColor('gray'),

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

                SelectFilter::make('goods_id')
                    ->label('关联商品')
                    ->options(
                        Goods::query()
                            ->where('type', Goods::AUTOMATIC_DELIVERY)
                            ->pluck('gd_name', 'id')
                    )
                    ->searchable(),

                SelectFilter::make('status')
                    ->label('卡密状态')
                    ->options([
                        Carmis::STATUS_UNSOLD => '未售出',
                        Carmis::STATUS_SOLD => '已售出',
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
