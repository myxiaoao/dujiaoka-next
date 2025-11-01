<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Filament\Resources\Pays\Schemas;

use App\Models\Pay;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PayForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('支付网关信息')
                    ->columnSpan('full')
                    ->schema([
                        TextInput::make('pay_name')
                            ->label('支付方式名称')
                            ->required()
                            ->maxLength(200),

                        TextInput::make('pay_check')
                            ->label('支付标识')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(200)
                            ->helperText('唯一标识，如: alipay'),

                        TextInput::make('pay_handleroute')
                            ->label('支付处理路由')
                            ->required()
                            ->maxLength(200)
                            ->helperText('如: /pay/alipay'),

                        Radio::make('pay_method')
                            ->label('支付类型')
                            ->options([
                                Pay::METHOD_JUMP => '跳转',
                                Pay::METHOD_SCAN => '扫码',
                            ])
                            ->default(Pay::METHOD_JUMP)
                            ->required()
                            ->inline(),

                        Radio::make('pay_client')
                            ->label('适用端')
                            ->options([
                                Pay::PAY_CLIENT_PC => 'PC端',
                                Pay::PAY_CLIENT_MOBILE => '移动端',
                                Pay::PAY_CLIENT_ALL => '通用',
                            ])
                            ->default(Pay::PAY_CLIENT_PC)
                            ->required()
                            ->inline(),

                        TextInput::make('merchant_id')
                            ->label('商户ID')
                            ->required()
                            ->maxLength(200),

                        Textarea::make('merchant_key')
                            ->label('商户密钥')
                            ->rows(3),

                        Textarea::make('merchant_pem')
                            ->label('商户证书')
                            ->required()
                            ->rows(5),

                        Toggle::make('is_open')
                            ->label('是否启用')
                            ->default(true)
                            ->inline(false),
                    ]),
            ]);
    }
}
