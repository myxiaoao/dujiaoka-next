<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Filament\Exports;

use App\Models\Carmis;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class CarmisExporter extends Exporter
{
    protected static ?string $model = Carmis::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('goods.gd_name')
                ->label('所属商品'),
            ExportColumn::make('carmi')
                ->label('卡密内容'),
            ExportColumn::make('created_at')
                ->label('创建时间'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = '卡密导出已完成，成功导出 '.Number::format($export->successful_rows).' 条记录。';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' 失败 '.Number::format($failedRowsCount).' 条记录。';
        }

        return $body;
    }
}
