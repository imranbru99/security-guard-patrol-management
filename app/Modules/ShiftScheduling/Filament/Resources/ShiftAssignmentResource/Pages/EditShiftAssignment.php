<?php

declare(strict_types=1);

namespace App\Modules\ShiftScheduling\Filament\Resources\ShiftAssignmentResource\Pages;

use App\Modules\ShiftScheduling\Filament\Resources\ShiftAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditShiftAssignment extends EditRecord
{
    protected static string $resource = ShiftAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
