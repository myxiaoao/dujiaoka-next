<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Page
{
    protected string $view = 'filament.pages.system-setting';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = '系统设置';

    protected static string|\UnitEnum|null $navigationGroup = '系统配置';

    protected static ?string $title = '系统设置';

    protected static ?int $navigationSort = 4;

    public ?array $data = [];

    public function mount(): void
    {
        $this->data = Cache::get('system-setting', []);
    }

    public function schema(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Tabs::make('Settings')
                    ->tabs([
                        // 基础设置
                        Tabs\Tab::make('基础设置')
                            ->schema([
                                TextInput::make('title')
                                    ->label('网站标题')
                                    ->required()
                                    ->maxLength(255),

                                FileUpload::make('img_logo')
                                    ->label('网站Logo（图片）')
                                    ->image()
                                    ->disk('public')
                                    ->directory('logos')
                                    ->maxSize(2048),

                                TextInput::make('text_logo')
                                    ->label('网站Logo（文字）')
                                    ->maxLength(255),

                                TextInput::make('keywords')
                                    ->label('SEO关键词')
                                    ->maxLength(255),

                                Textarea::make('description')
                                    ->label('SEO描述')
                                    ->rows(3)
                                    ->maxLength(500),

                                Select::make('template')
                                    ->label('网站模板')
                                    ->required()
                                    ->options(config('dujiaoka.templates', []))
                                    ->default('default'),

                                Select::make('language')
                                    ->label('网站语言')
                                    ->required()
                                    ->options(config('dujiaoka.language', []))
                                    ->default('zh_CN'),

                                TextInput::make('manage_email')
                                    ->label('管理员邮箱')
                                    ->email()
                                    ->maxLength(255),

                                TextInput::make('order_expire_time')
                                    ->label('订单过期时间（分钟）')
                                    ->numeric()
                                    ->required()
                                    ->default(5)
                                    ->minValue(1)
                                    ->maxValue(1440),

                                Toggle::make('is_open_anti_red')
                                    ->label('开启防红跳转')
                                    ->default(false),

                                Toggle::make('is_open_img_code')
                                    ->label('开启图形验证码')
                                    ->default(false),

                                Toggle::make('is_open_search_pwd')
                                    ->label('开启订单查询密码')
                                    ->default(false),

                                Toggle::make('is_open_google_translate')
                                    ->label('开启Google翻译')
                                    ->default(false),

                                RichEditor::make('notice')
                                    ->label('首页公告')
                                    ->toolbarButtons([
                                        'bold',
                                        'italic',
                                        'link',
                                        'bulletList',
                                        'orderedList',
                                        'h2',
                                        'h3',
                                    ]),

                                Textarea::make('footer')
                                    ->label('页脚信息')
                                    ->rows(3),
                            ]),

                        // 订单推送设置
                        Tabs\Tab::make('订单推送设置')
                            ->schema([
                                Section::make('Server酱推送')
                                    ->schema([
                                        Toggle::make('is_open_server_jiang')
                                            ->label('开启Server酱推送')
                                            ->default(false)
                                            ->reactive(),

                                        TextInput::make('server_jiang_token')
                                            ->label('Server酱Token')
                                            ->maxLength(255)
                                            ->visible(fn ($get) => $get('is_open_server_jiang')),
                                    ]),

                                Section::make('Telegram推送')
                                    ->schema([
                                        Toggle::make('is_open_telegram_push')
                                            ->label('开启Telegram推送')
                                            ->default(false)
                                            ->reactive(),

                                        TextInput::make('telegram_bot_token')
                                            ->label('Telegram Bot Token')
                                            ->maxLength(255)
                                            ->visible(fn ($get) => $get('is_open_telegram_push')),

                                        TextInput::make('telegram_userid')
                                            ->label('Telegram User ID')
                                            ->maxLength(255)
                                            ->visible(fn ($get) => $get('is_open_telegram_push')),
                                    ]),

                                Section::make('Bark推送')
                                    ->schema([
                                        Toggle::make('is_open_bark_push')
                                            ->label('开启Bark推送')
                                            ->default(false)
                                            ->reactive(),

                                        Toggle::make('is_open_bark_push_url')
                                            ->label('自定义Bark服务器')
                                            ->default(false)
                                            ->visible(fn ($get) => $get('is_open_bark_push')),

                                        TextInput::make('bark_server')
                                            ->label('Bark服务器地址')
                                            ->maxLength(255)
                                            ->visible(fn ($get) => $get('is_open_bark_push') && $get('is_open_bark_push_url')),

                                        TextInput::make('bark_token')
                                            ->label('Bark Token')
                                            ->maxLength(255)
                                            ->visible(fn ($get) => $get('is_open_bark_push')),
                                    ]),

                                Section::make('企业微信机器人推送')
                                    ->schema([
                                        Toggle::make('is_open_qywxbot_push')
                                            ->label('开启企业微信机器人推送')
                                            ->default(false)
                                            ->reactive(),

                                        TextInput::make('qywxbot_key')
                                            ->label('企业微信机器人Key')
                                            ->maxLength(255)
                                            ->visible(fn ($get) => $get('is_open_qywxbot_push')),
                                    ]),
                            ]),

                        // 邮件设置
                        Tabs\Tab::make('邮件设置')
                            ->schema([
                                TextInput::make('driver')
                                    ->label('邮件驱动')
                                    ->required()
                                    ->default('smtp')
                                    ->maxLength(50),

                                TextInput::make('host')
                                    ->label('SMTP服务器')
                                    ->maxLength(255),

                                TextInput::make('port')
                                    ->label('SMTP端口')
                                    ->numeric()
                                    ->default(587),

                                TextInput::make('username')
                                    ->label('SMTP用户名')
                                    ->maxLength(255),

                                TextInput::make('password')
                                    ->label('SMTP密码')
                                    ->password()
                                    ->maxLength(255),

                                TextInput::make('encryption')
                                    ->label('加密方式')
                                    ->maxLength(50)
                                    ->placeholder('ssl / tls'),

                                TextInput::make('from_address')
                                    ->label('发件人邮箱')
                                    ->email()
                                    ->maxLength(255),

                                TextInput::make('from_name')
                                    ->label('发件人名称')
                                    ->maxLength(255)
                                    ->default('独角发卡'),
                            ]),

                        // 极验设置
                        Tabs\Tab::make('极验设置')
                            ->schema([
                                TextInput::make('geetest_id')
                                    ->label('极验ID')
                                    ->maxLength(255),

                                TextInput::make('geetest_key')
                                    ->label('极验Key')
                                    ->maxLength(255),

                                Toggle::make('is_open_geetest')
                                    ->label('开启极验验证')
                                    ->default(false),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('保存设置')
                ->action('saveSettings')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('确认保存设置')
                ->modalDescription('保存后部分设置需要重启PHP Worker才能生效。确定要保存吗？')
                ->modalSubmitActionLabel('确定保存'),
        ];
    }

    public function saveSettings(): void
    {
        // 从 schema 获取表单数据
        $data = $this->schema->getState();

        try {
            Cache::put('system-setting', $data);

            // 同时更新组件的 data 属性
            $this->data = $data;

            Notification::make()
                ->success()
                ->title('保存成功')
                ->body('系统设置已保存。部分配置需要重启PHP Worker才能生效。')
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('保存失败')
                ->body('保存设置时发生错误：'.$e->getMessage())
                ->send();
        }
    }
}
