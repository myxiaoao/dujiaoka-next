<?php

namespace App\Filament\Resources\Goods\Schemas;

use App\Models\Goods;
use App\Models\GoodsGroup;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class GoodsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('基本信息')
                    ->schema([
                        TextInput::make('gd_name')
                            ->label('商品名称')
                            ->required()
                            ->maxLength(200),

                        TextInput::make('gd_description')
                            ->label('商品描述')
                            ->required()
                            ->maxLength(400),

                        TextInput::make('gd_keywords')
                            ->label('商品关键词')
                            ->required()
                            ->maxLength(200),

                        Select::make('group_id')
                            ->label('商品分类')
                            ->options(GoodsGroup::query()->pluck('gp_name', 'id'))
                            ->required()
                            ->searchable(),

                        FileUpload::make('picture')
                            ->label('商品图片')
                            ->image()
                            ->directory('goods')
                            ->helperText('推荐尺寸：400x400 像素'),
                    ])
                    ->columns(2),

                Section::make('商品类型与定价')
                    ->schema([
                        Radio::make('type')
                            ->label('发货类型')
                            ->options([
                                Goods::AUTOMATIC_DELIVERY => '自动发货',
                                Goods::MANUAL_PROCESSING => '人工处理',
                            ])
                            ->default(Goods::AUTOMATIC_DELIVERY)
                            ->required()
                            ->inline()
                            ->inlineLabel(false),

                        TextInput::make('retail_price')
                            ->label('零售价')
                            ->numeric()
                            ->default(0)
                            ->prefix('¥')
                            ->helperText('显示的原价，可以为0'),

                        TextInput::make('actual_price')
                            ->label('实际售价')
                            ->numeric()
                            ->default(0)
                            ->prefix('¥')
                            ->required()
                            ->helperText('实际销售价格'),
                    ])
                    ->columns(3),

                Section::make('库存与销售')
                    ->schema([
                        TextInput::make('in_stock')
                            ->label('库存数量')
                            ->numeric()
                            ->default(0)
                            ->helperText('如果是自动发货，库存数量为卡密数量'),

                        TextInput::make('sales_volume')
                            ->label('销量')
                            ->numeric()
                            ->default(0),

                        TextInput::make('buy_limit_num')
                            ->label('单次购买限制')
                            ->numeric()
                            ->default(0)
                            ->helperText('0表示不限制，否则限制单次购买数量'),

                        TextInput::make('ord')
                            ->label('排序权重')
                            ->numeric()
                            ->default(1)
                            ->helperText('数值越大越靠前')
                            ->required(),
                    ])
                    ->columns(4),

                Section::make('商品详情')
                    ->schema([
                        RichEditor::make('buy_prompt')
                            ->label('购买提示')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'link',
                                'bulletList',
                                'orderedList',
                            ])
                            ->columnSpanFull(),

                        RichEditor::make('description')
                            ->label('商品详细介绍')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'link',
                                'bulletList',
                                'orderedList',
                            ])
                            ->columnSpanFull(),
                    ]),

                Section::make('高级配置')
                    ->schema([
                        Textarea::make('other_ipu_cnf')
                            ->label('自定义输入字段')
                            ->rows(5)
                            ->helperText('JSON格式配置，例如：{"qq":"QQ号码","phone":"手机号"}')
                            ->columnSpanFull(),

                        Textarea::make('wholesale_price_cnf')
                            ->label('批发价格配置')
                            ->rows(5)
                            ->helperText('JSON格式配置批发价，例如：{"10":9.8,"50":9.5}')
                            ->columnSpanFull(),

                        Textarea::make('api_hook')
                            ->label('API回调地址')
                            ->rows(3)
                            ->helperText('订单完成后的回调地址')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('状态')
                    ->schema([
                        Toggle::make('is_open')
                            ->label('是否上架')
                            ->default(true)
                            ->inline(false),
                    ]),
            ]);
    }
}
