<?php

declare(strict_types=1);

namespace App\Modules\ClientsSites\Filament\Resources\ClientResource\Pages;

use App\Modules\ClientsSites\Filament\Resources\ClientResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;
}
