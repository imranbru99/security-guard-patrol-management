<?php

declare(strict_types=1);

namespace App\Modules\Incidents\Filament\Resources\IncidentResource\Pages;

use App\Modules\Incidents\Filament\Resources\IncidentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListIncidents extends ListRecords
{
    protected static string $resource = IncidentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
