<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Filament\Resources\Carmis\Schemas;

use App\Models\Carmis;
use App\Models\Goods;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CarmisForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('卡密信息')
                    ->columnSpan('full')
                    ->schema([
                        Select::make('goods_id')
                            ->label('关联商品')
                            ->options(
                                Goods::query()
                                    ->where('type', Goods::AUTOMATIC_DELIVERY)
                                    ->pluck('gd_name', 'id')
                            )
                            ->required()
                            ->searchable()
                            ->helperText('只显示自动发货类型的商品'),

                        Radio::make('status')
                            ->label('卡密状态')
                            ->options([
                                Carmis::STATUS_UNSOLD => '未售出',
                                Carmis::STATUS_SOLD => '已售出',
                            ])
                            ->default(Carmis::STATUS_UNSOLD)
                            ->required()
                            ->inline(),

                        Toggle::make('is_loop')
                            ->label('是否循环使用')
                            ->default(false)
                            ->helperText('启用后，卡密售出后会自动标记为未售出，可重复销售')
                            ->inline(false),
                    ]),

                Section::make('卡密内容')
                    ->columnSpan('full')
                    ->schema([
                        Textarea::make('carmi')
                            ->label('卡密')
                            ->required()
                            ->rows(5)
                            ->helperText('卡密内容，可以是账号密码、激活码等')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
