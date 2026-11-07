<?php

declare(strict_types=1);

namespace App\Modules\VehiclesGate\Filament\Resources\VehicleLogResource\Pages;

use App\Modules\VehiclesGate\Filament\Resources\VehicleLogResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateVehicleLog extends CreateRecord
{
    protected static string $resource = VehicleLogResource::class;
}
