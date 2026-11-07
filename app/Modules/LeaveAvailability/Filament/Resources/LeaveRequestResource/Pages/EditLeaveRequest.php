<?php

declare(strict_types=1);

namespace App\Modules\LeaveAvailability\Filament\Resources\LeaveRequestResource\Pages;

use App\Modules\LeaveAvailability\Filament\Resources\LeaveRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditLeaveRequest extends EditRecord
{
    protected static string $resource = LeaveRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
