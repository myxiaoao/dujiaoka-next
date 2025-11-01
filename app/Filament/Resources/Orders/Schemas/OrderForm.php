<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Order;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('订单信息')
                    ->schema([
                        TextInput::make('order_sn')
                            ->label('订单号')
                            ->disabled(),

                        TextInput::make('title')
                            ->label('订单标题')
                            ->disabled(),

                        TextInput::make('email')
                            ->label('邮箱')
                            ->disabled(),

                        TextInput::make('buy_ip')
                            ->label('购买IP')
                            ->disabled(),
                    ])
                    ->columns(2),

                Section::make('商品信息')
                    ->schema([
                        Placeholder::make('goods_name')
                            ->label('商品名称')
                            ->content(fn ($record) => $record?->goods?->gd_name ?? '-'),

                        TextInput::make('goods_price')
                            ->label('商品单价')
                            ->prefix('¥')
                            ->disabled(),

                        TextInput::make('buy_amount')
                            ->label('购买数量')
                            ->disabled(),

                        TextInput::make('total_price')
                            ->label('商品总价')
                            ->prefix('¥')
                            ->disabled(),
                    ])
                    ->columns(4),

                Section::make('优惠信息')
                    ->schema([
                        Placeholder::make('coupon_code')
                            ->label('优惠券')
                            ->content(fn ($record) => $record?->coupon?->coupon ?? '无'),

                        TextInput::make('coupon_discount_price')
                            ->label('优惠券折扣')
                            ->prefix('¥')
                            ->disabled(),

                        TextInput::make('wholesale_discount_price')
                            ->label('批发折扣')
                            ->prefix('¥')
                            ->disabled(),

                        TextInput::make('actual_price')
                            ->label('实际支付金额')
                            ->prefix('¥')
                            ->disabled(),
                    ])
                    ->columns(4),

                Section::make('支付信息')
                    ->schema([
                        Placeholder::make('pay_name')
                            ->label('支付方式')
                            ->content(fn ($record) => $record?->pay?->pay_name ?? '-'),

                        TextInput::make('trade_no')
                            ->label('交易流水号')
                            ->disabled()
                            ->columnSpan(2),
                    ])
                    ->columns(3),

                Section::make('订单状态')
                    ->schema([
                        Radio::make('status')
                            ->label('订单状态')
                            ->options([
                                Order::STATUS_PENDING => '待支付',
                                Order::STATUS_PROCESSING => '处理中',
                                Order::STATUS_COMPLETED => '已完成',
                                Order::STATUS_FAILURE => '失败',
                                Order::STATUS_ABNORMAL => '异常',
                            ])
                            ->required(),

                        TextInput::make('search_pwd')
                            ->label('查询密码')
                            ->helperText('用户查询订单的密码'),
                    ])
                    ->columns(2),

                Section::make('其他信息')
                    ->schema([
                        Textarea::make('info')
                            ->label('订单信息')
                            ->rows(5)
                            ->helperText('订单的额外信息，如卡密内容等')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
