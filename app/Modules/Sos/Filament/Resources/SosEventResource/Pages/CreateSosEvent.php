<?php

declare(strict_types=1);

namespace App\Modules\Sos\Filament\Resources\SosEventResource\Pages;

use App\Modules\Sos\Filament\Resources\SosEventResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateSosEvent extends CreateRecord
{
    protected static string $resource = SosEventResource::class;
}
