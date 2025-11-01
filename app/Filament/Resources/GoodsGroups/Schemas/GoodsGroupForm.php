<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Filament\Resources\GoodsGroups\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class GoodsGroupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('分类信息')
                    ->schema([
                        TextInput::make('gp_name')
                            ->label('分类名称')
                            ->required()
                            ->maxLength(200),

                        TextInput::make('ord')
                            ->label('排序权重')
                            ->numeric()
                            ->default(1)
                            ->helperText('数值越大越靠前')
                            ->required(),

                        Toggle::make('is_open')
                            ->label('是否启用')
                            ->default(true)
                            ->inline(false),
                    ])
                    ->columns(2),
            ]);
    }
}
