<?php

declare(strict_types=1);

namespace App\Modules\ShiftScheduling\Filament\Resources\ShiftAssignmentResource\Pages;

use App\Modules\ShiftScheduling\Filament\Resources\ShiftAssignmentResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateShiftAssignment extends CreateRecord
{
    protected static string $resource = ShiftAssignmentResource::class;
}
