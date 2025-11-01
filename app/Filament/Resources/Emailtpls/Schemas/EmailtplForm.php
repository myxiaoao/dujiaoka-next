<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Filament\Resources\Emailtpls\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EmailtplForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('邮件模板信息')
                    ->schema([
                        TextInput::make('tpl_name')
                            ->label('模板名称')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('tpl_token')
                            ->label('模板标识')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText('唯一标识，如: order_paid'),

                        TextInput::make('tpl_subject')
                            ->label('邮件主题')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('tpl_content')
                            ->label('邮件内容')
                            ->required()
                            ->rows(10)
                            ->helperText('支持变量替换，如 {order_sn}, {title}')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
