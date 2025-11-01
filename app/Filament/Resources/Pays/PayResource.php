<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Filament\Resources\Pays;

use App\Filament\Resources\Pays\Pages\CreatePay;
use App\Filament\Resources\Pays\Pages\EditPay;
use App\Filament\Resources\Pays\Pages\ListPays;
use App\Filament\Resources\Pays\Schemas\PayForm;
use App\Filament\Resources\Pays\Tables\PaysTable;
use App\Models\Pay;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PayResource extends Resource
{
    protected static ?string $model = Pay::class;

    protected static ?string $navigationLabel = '支付方式';

    protected static ?string $modelLabel = '支付方式';

    protected static ?string $pluralModelLabel = '支付方式';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return PayForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaysTable::configure($table);
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
            'index' => ListPays::route('/'),
            'create' => CreatePay::route('/create'),
            'edit' => EditPay::route('/{record}/edit'),
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
