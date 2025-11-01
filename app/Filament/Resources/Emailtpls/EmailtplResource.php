<?php

namespace App\Filament\Resources\Emailtpls;

use App\Filament\Resources\Emailtpls\Pages\CreateEmailtpl;
use App\Filament\Resources\Emailtpls\Pages\EditEmailtpl;
use App\Filament\Resources\Emailtpls\Pages\ListEmailtpls;
use App\Filament\Resources\Emailtpls\Schemas\EmailtplForm;
use App\Filament\Resources\Emailtpls\Tables\EmailtplsTable;
use App\Models\Emailtpl;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmailtplResource extends Resource
{
    protected static ?string $model = Emailtpl::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return EmailtplForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmailtplsTable::configure($table);
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
            'index' => ListEmailtpls::route('/'),
            'create' => CreateEmailtpl::route('/create'),
            'edit' => EditEmailtpl::route('/{record}/edit'),
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
