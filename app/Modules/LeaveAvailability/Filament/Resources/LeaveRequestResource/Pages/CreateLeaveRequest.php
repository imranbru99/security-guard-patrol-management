<?php

declare(strict_types=1);

namespace App\Modules\LeaveAvailability\Filament\Resources\LeaveRequestResource\Pages;

use App\Modules\LeaveAvailability\Filament\Resources\LeaveRequestResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateLeaveRequest extends CreateRecord
{
    protected static string $resource = LeaveRequestResource::class;
}
