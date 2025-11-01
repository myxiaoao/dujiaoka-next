<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Filament\Resources\Emailtpls\Pages;

use App\Filament\Resources\Emailtpls\EmailtplResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmailtpl extends CreateRecord
{
    protected static string $resource = EmailtplResource::class;
}
