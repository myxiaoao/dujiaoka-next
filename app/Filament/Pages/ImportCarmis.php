<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Filament\Pages;

use App\Models\Carmis;
use App\Models\Goods;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class ImportCarmis extends Page
{
    protected string $view = 'filament.pages.import-carmis';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-down-tray';

    protected static ?string $navigationLabel = '导入卡密';

    protected static string|\UnitEnum|null $navigationGroup = '商品管理';

    protected static ?string $title = '批量导入卡密';

    protected static ?int $navigationSort = 4;

    public ?array $data = [];

    public function schema(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('导入卡密')
                    ->schema([
                        Select::make('goods_id')
                            ->label('选择商品')
                            ->required()
                            ->options(
                                Goods::query()
                                    ->where('type', Goods::AUTOMATIC_DELIVERY)
                                    ->pluck('gd_name', 'id')
                            )
                            ->searchable()
                            ->placeholder('请选择要导入卡密的商品'),

                        Textarea::make('carmis_list')
                            ->label('卡密列表')
                            ->rows(15)
                            ->placeholder('每行一个卡密，支持多种格式：
账号----密码
卡号----密码
纯卡密')
                            ->helperText('直接粘贴卡密列表，每行一个。可以使用 ---- 分隔账号密码，也可以直接粘贴卡密。'),

                        FileUpload::make('carmis_txt')
                            ->label('或上传TXT文件')
                            ->disk('public')
                            ->directory('temp')
                            ->acceptedFileTypes(['text/plain'])
                            ->maxSize(5120)
                            ->helperText('支持上传TXT文件导入，最大5MB。文件内容格式同上。'),

                        Toggle::make('remove_duplication')
                            ->label('自动去重')
                            ->default(true)
                            ->helperText('开启后会自动去除重复的卡密'),
                    ]),
            ]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('import')
                ->label('开始导入')
                ->action('importCarmis')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('确认导入卡密')
                ->modalDescription('确定要导入这些卡密吗？导入后将无法撤销。')
                ->modalSubmitActionLabel('确定导入'),
        ];
    }

    public function importCarmis(): void
    {
        $data = $this->schema->getState();

        // 验证至少有一个输入源
        if (empty($data['carmis_list']) && empty($data['carmis_txt'])) {
            Notification::make()
                ->danger()
                ->title('导入失败')
                ->body('请输入卡密列表或上传TXT文件')
                ->send();

            return;
        }

        $carmisContent = '';

        // 优先使用文件上传
        if (! empty($data['carmis_txt'])) {
            try {
                $carmisContent = Storage::disk('public')->get($data['carmis_txt']);
            } catch (\Exception $e) {
                Notification::make()
                    ->danger()
                    ->title('文件读取失败')
                    ->body('无法读取上传的文件：'.$e->getMessage())
                    ->send();

                return;
            }
        }

        // 如果没有文件，使用文本框输入
        if (empty($carmisContent) && ! empty($data['carmis_list'])) {
            $carmisContent = $data['carmis_list'];
        }

        // 解析卡密数据
        $carmisData = [];
        $tempList = explode(PHP_EOL, $carmisContent);

        foreach ($tempList as $val) {
            $trimmed = trim($val);
            if ($trimmed !== '') {
                $carmisData[] = [
                    'goods_id' => $data['goods_id'],
                    'carmi' => $trimmed,
                    'status' => Carmis::STATUS_UNSOLD,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // 去重
        if ($data['remove_duplication']) {
            $carmisData = collect($carmisData)->unique('carmi')->values()->toArray();
        }

        if (empty($carmisData)) {
            Notification::make()
                ->warning()
                ->title('导入失败')
                ->body('没有找到有效的卡密数据')
                ->send();

            return;
        }

        try {
            // 批量插入
            Carmis::query()->insert($carmisData);

            // 删除临时文件
            if (! empty($data['carmis_txt'])) {
                Storage::disk('public')->delete($data['carmis_txt']);
            }

            // 清空表单
            $this->reset('data');

            Notification::make()
                ->success()
                ->title('导入成功')
                ->body('成功导入 '.count($carmisData).' 个卡密')
                ->send();

            // 重定向到卡密列表
            $this->redirect(route('filament.admin.resources.carmis.index'));
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('导入失败')
                ->body('导入过程中发生错误：'.$e->getMessage())
                ->send();
        }
    }
}
