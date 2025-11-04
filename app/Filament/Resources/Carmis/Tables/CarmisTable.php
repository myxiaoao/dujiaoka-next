<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Filament\Resources\Carmis\Tables;

use App\Filament\Exports\CarmisExporter;
use App\Filament\Imports\CarmisImporter;
use App\Models\Carmis;
use App\Models\Goods;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\ImportAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class CarmisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('goods.gd_name')
                    ->label('关联商品')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->weight('bold')
                    ->badge()
                    ->color('info'),

                TextColumn::make('status')
                    ->label('状态')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        Carmis::STATUS_UNSOLD => 'success',
                        Carmis::STATUS_SOLD => 'gray',
                    })
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        Carmis::STATUS_UNSOLD => '可用',
                        Carmis::STATUS_SOLD => '已售',
                    })
                    ->sortable(),

                TextColumn::make('carmi')
                    ->label('卡密内容')
                    ->limit(40)
                    ->searchable()
                    ->copyable()
                    ->tooltip('点击复制')
                    ->fontFamily('mono'),

                IconColumn::make('is_loop')
                    ->label('循环')
                    ->boolean()
                    ->trueIcon('heroicon-o-arrow-path')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('info')
                    ->falseColor('gray')
                    ->tooltip(fn ($record) => $record->is_loop ? '循环使用' : '一次性'),

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
                ViewAction::make(),
                EditAction::make(),
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(CarmisImporter::class)
                    ->label('导入卡密 (CSV/Excel)')
                    ->color('primary')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->modalHeading('导入卡密')
                    ->modalDescription('支持导入 CSV 或 Excel 文件，请确保文件包含必填字段：商品ID、卡密内容')
                    ->modalSubmitActionLabel('开始导入'),
                ExportAction::make()
                    ->exporter(CarmisExporter::class)
                    ->label('导出卡密')
                    ->color('success')
                    ->icon('heroicon-o-arrow-down-tray'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->groups([
                Group::make('goods.gd_name')
                    ->label('关联商品')
                    ->collapsible(),

                Group::make('status')
                    ->label('卡密状态')
                    ->collapsible()
                    ->getTitleFromRecordUsing(fn (Carmis $record): string => match ($record->status) {
                        Carmis::STATUS_UNSOLD => '可用',
                        Carmis::STATUS_SOLD => '已售',
                        default => '未知',
                    }),

                Group::make('is_loop')
                    ->label('循环状态')
                    ->collapsible()
                    ->getTitleFromRecordUsing(fn (Carmis $record): string => $record->is_loop ? '循环使用' : '一次性'),

                Group::make('created_at')
                    ->label('创建日期')
                    ->date()
                    ->collapsible(),
            ])
            ->defaultSort('id', 'desc');
    }
}
