<?php

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

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

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
}
