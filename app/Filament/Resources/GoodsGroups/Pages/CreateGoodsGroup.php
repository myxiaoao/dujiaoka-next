<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Filament\Resources\GoodsGroups\Pages;

use App\Filament\Resources\GoodsGroups\GoodsGroupResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGoodsGroup extends CreateRecord
{
    protected static string $resource = GoodsGroupResource::class;
}
