<?php

namespace App\Filament\Resources\Emailtpls\Pages;

use App\Filament\Resources\Emailtpls\EmailtplResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditEmailtpl extends EditRecord
{
    protected static string $resource = EmailtplResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
