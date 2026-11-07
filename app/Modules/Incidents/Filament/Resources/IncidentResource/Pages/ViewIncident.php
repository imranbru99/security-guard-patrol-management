<?php

declare(strict_types=1);

namespace App\Modules\Incidents\Filament\Resources\IncidentResource\Pages;

use App\Modules\Incidents\Filament\Resources\IncidentResource;
use App\Modules\Incidents\Models\Incident;
use App\Modules\Incidents\Services\IncidentEvidenceAccessLogger;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

final class ViewIncident extends ViewRecord
{
    protected static string $resource = IncidentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);

        $incident = $this->getRecord();
        if (! $incident instanceof Incident) {
            return;
        }

        $logger = app(IncidentEvidenceAccessLogger::class);
        $actorId = auth()->id();
        $incident->loadMissing('media.mediaAsset');

        foreach ($incident->media as $media) {
            $logger->logView($media, is_int($actorId) ? $actorId : null);
        }
    }
}
