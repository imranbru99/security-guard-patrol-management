<?php

declare(strict_types=1);

namespace App\Modules\AttendanceTracking\Filament\Resources\AttendanceSummaryResource\Pages;

use App\Modules\AttendanceTracking\Filament\Resources\AttendanceSummaryResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateAttendanceSummary extends CreateRecord
{
    protected static string $resource = AttendanceSummaryResource::class;
}
