<?php

declare(strict_types=1);

namespace App\Modules\Incidents\Filament\Resources\IncidentResource\Pages;

use App\Modules\Incidents\Filament\Resources\IncidentResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateIncident extends CreateRecord
{
    protected static string $resource = IncidentResource::class;
}
