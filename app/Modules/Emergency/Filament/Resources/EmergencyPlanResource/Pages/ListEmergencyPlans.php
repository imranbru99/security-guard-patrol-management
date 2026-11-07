<?php

declare(strict_types=1);

namespace App\Modules\Emergency\Filament\Resources\EmergencyPlanResource\Pages;

use App\Modules\Emergency\Filament\Resources\EmergencyPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListEmergencyPlans extends ListRecords
{
    protected static string $resource = EmergencyPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
