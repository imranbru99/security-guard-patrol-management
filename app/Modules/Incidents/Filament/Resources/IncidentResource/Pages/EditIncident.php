<?php

declare(strict_types=1);

namespace App\Modules\Incidents\Filament\Resources\IncidentResource\Pages;

use App\Modules\Incidents\Filament\Resources\IncidentResource;
use Filament\Resources\Pages\EditRecord;

final class EditIncident extends EditRecord
{
    protected static string $resource = IncidentResource::class;
}
