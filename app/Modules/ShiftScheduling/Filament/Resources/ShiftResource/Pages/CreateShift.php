<?php

declare(strict_types=1);

namespace App\Modules\ShiftScheduling\Filament\Resources\ShiftResource\Pages;

use App\Modules\ShiftScheduling\Filament\Resources\ShiftResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateShift extends CreateRecord
{
    protected static string $resource = ShiftResource::class;
}
