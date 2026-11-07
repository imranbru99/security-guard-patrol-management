<?php

declare(strict_types=1);

namespace App\Modules\Hazards\Filament\Resources\HazardResource\Pages;

use App\Modules\Hazards\Filament\Resources\HazardResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateHazard extends CreateRecord
{
    protected static string $resource = HazardResource::class;
}
