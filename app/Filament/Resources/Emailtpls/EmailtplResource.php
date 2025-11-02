<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

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

    protected static ?string $navigationLabel = '邮件模板';

    protected static ?string $modelLabel = '邮件模板';

    protected static ?string $pluralModelLabel = '邮件模板';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static string|\UnitEnum|null $navigationGroup = '系统配置';

    protected static ?int $navigationSort = 2;

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
