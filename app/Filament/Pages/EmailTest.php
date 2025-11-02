<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Mail\MailServiceProvider;
use Illuminate\Support\Facades\Mail;

class EmailTest extends Page
{
    protected string $view = 'filament.pages.email-test';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-paper-airplane';

    protected static ?string $navigationLabel = '邮件测试';

    protected static string|\UnitEnum|null $navigationGroup = '系统配置';

    protected static ?string $title = '邮件测试';

    protected static ?int $navigationSort = 3;

    public ?array $data = [];

    public function schema(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('邮件配置')
                    ->schema([
                        TextInput::make('to')
                            ->label('收件人邮箱')
                            ->email()
                            ->required()
                            ->placeholder('请输入收件人邮箱地址'),

                        TextInput::make('title')
                            ->label('邮件标题')
                            ->required()
                            ->default('这是一条测试邮件'),

                        RichEditor::make('body')
                            ->label('邮件正文')
                            ->required()
                            ->default('这是一条测试邮件的正文内容<br/><br/>正文比较长<br/><br/>非常长<br/><br/>测试测试测试')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'link',
                                'bulletList',
                                'orderedList',
                                'h2',
                                'h3',
                                'redo',
                                'undo',
                            ]),
                    ]),
            ]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('send')
                ->label('发送测试邮件')
                ->action('sendTestEmail')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('确认发送测试邮件')
                ->modalDescription('确定要发送测试邮件吗？')
                ->modalSubmitActionLabel('确定发送'),
        ];
    }

    public function sendTestEmail(): void
    {
        $data = $this->schema->getState();

        $sysConfig = cache('system-setting', []);
        $mailConfig = [
            'driver' => $sysConfig['driver'] ?? 'smtp',
            'host' => $sysConfig['host'] ?? '',
            'port' => $sysConfig['port'] ?? '465',
            'username' => $sysConfig['username'] ?? '',
            'from' => [
                'address' => $sysConfig['from_address'] ?? '',
                'name' => $sysConfig['from_name'] ?? '独角发卡',
            ],
            'password' => $sysConfig['password'] ?? '',
            'encryption' => $sysConfig['encryption'] ?? 'ssl',
        ];

        // 覆盖 mail 配置
        config([
            'mail' => array_merge(config('mail'), $mailConfig),
        ]);

        // 重新注册驱动
        (new MailServiceProvider(app()))->register();

        try {
            Mail::send(['html' => 'email.mail'], ['body' => $data['body']], function ($message) use ($data) {
                $message->to($data['to'])->subject($data['title']);
            });

            Notification::make()
                ->success()
                ->title('测试邮件发送成功')
                ->body('测试邮件已成功发送到 '.$data['to'])
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('测试邮件发送失败')
                ->body('错误信息：'.$e->getMessage())
                ->send();
        }
    }
}
