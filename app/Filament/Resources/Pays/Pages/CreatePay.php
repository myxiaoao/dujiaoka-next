<?php

namespace App\Filament\Resources\Pays\Pages;

use App\Filament\Resources\Pays\PayResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePay extends CreateRecord
{
    protected static string $resource = PayResource::class;
}
