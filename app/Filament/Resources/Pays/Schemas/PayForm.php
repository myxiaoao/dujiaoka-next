<?php

namespace App\Filament\Resources\Pays\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PayForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('支付网关信息')
                    ->schema([
                        TextInput::make('pay_name')
                            ->label('支付方式名称')
                            ->required()
                            ->maxLength(200),

                        TextInput::make('pay_handleroute')
                            ->label('支付处理路由')
                            ->required()
                            ->maxLength(200)
                            ->helperText('如: /pay/alipay'),

                        TextInput::make('merchant_id')
                            ->label('商户ID')
                            ->maxLength(200),

                        Textarea::make('merchant_key')
                            ->label('商户密钥')
                            ->rows(3),

                        Textarea::make('merchant_pem')
                            ->label('商户证书')
                            ->rows(5),

                        Toggle::make('is_open')
                            ->label('是否启用')
                            ->default(true)
                            ->inline(false),
                    ])
                    ->columns(2),
            ]);
    }
}
