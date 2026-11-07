<?php

declare(strict_types=1);

namespace App\Modules\Inspections\Filament\Resources\InspectionResource\Pages;

use App\Modules\Inspections\Filament\Resources\InspectionResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateInspection extends CreateRecord
{
    protected static string $resource = InspectionResource::class;
}
