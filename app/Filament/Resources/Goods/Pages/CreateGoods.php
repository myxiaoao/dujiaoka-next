<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Filament\Resources\Goods\Pages;

use App\Filament\Resources\Goods\GoodsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGoods extends CreateRecord
{
    protected static string $resource = GoodsResource::class;
}
