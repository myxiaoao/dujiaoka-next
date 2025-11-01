<?php

namespace App\Filament\Resources\Carmis;

use App\Filament\Resources\Carmis\Pages\CreateCarmis;
use App\Filament\Resources\Carmis\Pages\EditCarmis;
use App\Filament\Resources\Carmis\Pages\ListCarmis;
use App\Filament\Resources\Carmis\Schemas\CarmisForm;
use App\Filament\Resources\Carmis\Tables\CarmisTable;
use App\Models\Carmis;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CarmisResource extends Resource
{
    protected static ?string $model = Carmis::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return CarmisForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CarmisTable::configure($table);
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
            'index' => ListCarmis::route('/'),
            'create' => CreateCarmis::route('/create'),
            'edit' => EditCarmis::route('/{record}/edit'),
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
