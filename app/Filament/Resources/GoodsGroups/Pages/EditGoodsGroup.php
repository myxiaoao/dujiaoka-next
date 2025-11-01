<?php

namespace App\Filament\Resources\GoodsGroups\Pages;

use App\Filament\Resources\GoodsGroups\GoodsGroupResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditGoodsGroup extends EditRecord
{
    protected static string $resource = GoodsGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
