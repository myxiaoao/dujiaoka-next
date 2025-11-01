<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Filament\Resources\Coupons\Schemas;

use App\Models\Coupon;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CouponForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('优惠券信息')
                    ->schema([
                        TextInput::make('coupon')
                            ->label('优惠券码')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Radio::make('type')
                            ->label('优惠券类型')
                            ->options([
                                Coupon::TYPE_FIXED_AMOUNT => '固定金额',
                                Coupon::TYPE_PERCENTAGE => '百分比折扣',
                            ])
                            ->default(Coupon::TYPE_FIXED_AMOUNT)
                            ->required()
                            ->inline(),

                        TextInput::make('discount')
                            ->label('折扣值')
                            ->numeric()
                            ->required()
                            ->helperText('固定金额时为具体金额，百分比时为折扣百分比（如10表示10%）'),

                        TextInput::make('used')
                            ->label('已使用次数')
                            ->numeric()
                            ->default(0)
                            ->disabled(),

                        TextInput::make('ret')
                            ->label('剩余次数')
                            ->numeric()
                            ->default(0)
                            ->helperText('0表示无限制使用'),
                    ])
                    ->columns(2),
            ]);
    }
}
