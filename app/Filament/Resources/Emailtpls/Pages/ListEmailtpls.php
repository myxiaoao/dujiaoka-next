<?php

namespace App\Filament\Resources\Emailtpls\Pages;

use App\Filament\Resources\Emailtpls\EmailtplResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmailtpls extends ListRecords
{
    protected static string $resource = EmailtplResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
