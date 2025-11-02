<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Filament\Resources\GoodsGroups;

use App\Filament\Resources\GoodsGroups\Pages\CreateGoodsGroup;
use App\Filament\Resources\GoodsGroups\Pages\EditGoodsGroup;
use App\Filament\Resources\GoodsGroups\Pages\ListGoodsGroups;
use App\Filament\Resources\GoodsGroups\Schemas\GoodsGroupForm;
use App\Filament\Resources\GoodsGroups\Tables\GoodsGroupsTable;
use App\Models\GoodsGroup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GoodsGroupResource extends Resource
{
    protected static ?string $model = GoodsGroup::class;

    protected static ?string $navigationLabel = '商品分类';

    protected static ?string $modelLabel = '商品分类';

    protected static ?string $pluralModelLabel = '商品分类';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolderOpen;

    protected static string|\UnitEnum|null $navigationGroup = '商品管理';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return GoodsGroupForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GoodsGroupsTable::configure($table);
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
            'index' => ListGoodsGroups::route('/'),
            'create' => CreateGoodsGroup::route('/create'),
            'edit' => EditGoodsGroup::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
