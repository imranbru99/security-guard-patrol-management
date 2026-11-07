<?php

declare(strict_types=1);

namespace App\Modules\Emergency\Filament\Resources\EmergencyPlanResource\Pages;

use App\Modules\Emergency\Filament\Resources\EmergencyPlanResource;
use Filament\Resources\Pages\EditRecord;

final class EditEmergencyPlan extends EditRecord
{
    protected static string $resource = EmergencyPlanResource::class;
}
