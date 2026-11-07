<?php

declare(strict_types=1);

namespace App\Modules\AttendanceTracking\Filament\Resources\AttendanceSummaryResource\Pages;

use App\Modules\AttendanceTracking\Filament\Resources\AttendanceSummaryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListAttendanceSummaries extends ListRecords
{
    protected static string $resource = AttendanceSummaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
