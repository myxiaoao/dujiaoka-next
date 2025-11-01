<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Filament\Resources\Coupons\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CouponForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('优惠券基本信息')
                    ->schema([
                        TextInput::make('coupon')
                            ->label('优惠券码')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(150)
                            ->helperText('优惠券的唯一标识码'),

                        TextInput::make('discount')
                            ->label('优惠金额')
                            ->numeric()
                            ->required()
                            ->prefix('¥')
                            ->step(0.01)
                            ->helperText('减免的金额'),

                        TextInput::make('ret')
                            ->label('剩余使用次数')
                            ->numeric()
                            ->default(0)
                            ->helperText('0表示无限制使用'),

                        Toggle::make('is_open')
                            ->label('是否启用')
                            ->default(true)
                            ->inline(false),
                    ])
                    ->columns(2),

                Section::make('关联商品')
                    ->schema([
                        Select::make('goods')
                            ->label('适用商品')
                            ->relationship('goods', 'gd_name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->helperText('不选择则适用于所有商品'),
                    ]),
            ]);
    }
}
