<?php

namespace App\Filament\Resources\GoodsGroups\Pages;

use App\Filament\Resources\GoodsGroups\GoodsGroupResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGoodsGroups extends ListRecords
{
    protected static string $resource = GoodsGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
