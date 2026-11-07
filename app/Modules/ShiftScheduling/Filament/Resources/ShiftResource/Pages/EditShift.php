<?php

declare(strict_types=1);

namespace App\Modules\ShiftScheduling\Filament\Resources\ShiftResource\Pages;

use App\Modules\ShiftScheduling\Filament\Resources\ShiftResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditShift extends EditRecord
{
    protected static string $resource = ShiftResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
