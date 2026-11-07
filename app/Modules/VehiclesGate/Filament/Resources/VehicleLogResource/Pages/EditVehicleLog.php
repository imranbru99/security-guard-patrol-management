<?php

declare(strict_types=1);

namespace App\Modules\VehiclesGate\Filament\Resources\VehicleLogResource\Pages;

use App\Modules\VehiclesGate\Filament\Resources\VehicleLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditVehicleLog extends EditRecord
{
    protected static string $resource = VehicleLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
