<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Filament\Imports;

use App\Models\Carmis;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class CarmisImporter extends Importer
{
    protected static ?string $model = Carmis::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('goods_id')
                ->label('商品ID')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer', 'exists:goods,id']),
            ImportColumn::make('carmi')
                ->label('卡密内容')
                ->requiredMapping()
                ->rules(['required', 'max:5000']),
            ImportColumn::make('status')
                ->label('状态')
                ->numeric()
                ->rules(['integer'])
                ->example('1')
                ->fillRecordUsing(function (Carmis $record, $state): void {
                    $record->status = $state ?? Carmis::STATUS_UNSOLD;
                }),
            ImportColumn::make('is_loop')
                ->label('循环使用')
                ->boolean()
                ->rules(['boolean'])
                ->example('0')
                ->fillRecordUsing(function (Carmis $record, $state): void {
                    $record->is_loop = $state ?? false;
                }),
        ];
    }

    public function resolveRecord(): Carmis
    {
        return new Carmis;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = '卡密导入已完成，成功导入 '.Number::format($import->successful_rows).' 条记录。';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' 失败 '.Number::format($failedRowsCount).' 条记录。';
        }

        return $body;
    }
}
