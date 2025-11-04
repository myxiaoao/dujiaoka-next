<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Filament\Resources\Goods;

use App\Filament\Resources\Goods\Pages\CreateGoods;
use App\Filament\Resources\Goods\Pages\EditGoods;
use App\Filament\Resources\Goods\Pages\ListGoods;
use App\Filament\Resources\Goods\Schemas\GoodsForm;
use App\Filament\Resources\Goods\Tables\GoodsTable;
use App\Models\Goods;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GoodsResource extends Resource
{
    protected static ?string $model = Goods::class;

    protected static ?string $navigationLabel = '商品列表';

    protected static ?string $modelLabel = '商品';

    protected static ?string $pluralModelLabel = '商品';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static string|\UnitEnum|null $navigationGroup = '商品管理';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'gd_name';

    protected static int $globalSearchResultsLimit = 20;

    public static function form(Schema $schema): Schema
    {
        return GoodsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GoodsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGoods::route('/'),
            'create' => CreateGoods::route('/create'),
            'edit' => EditGoods::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('is_open', 1)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'gd_name',
            'gd_description',
            'gd_keywords',
            'group.gp_name',
        ];
    }

    public static function getGlobalSearchResultTitle(\Illuminate\Database\Eloquent\Model $record): string
    {
        return $record->gd_name;
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            '分类' => $record->group?->gp_name ?? '未分类',
            '售价' => '¥'.number_format((float) $record->actual_price, 2),
            '状态' => $record->is_open ? '已上架' : '已下架',
        ];
    }
}
