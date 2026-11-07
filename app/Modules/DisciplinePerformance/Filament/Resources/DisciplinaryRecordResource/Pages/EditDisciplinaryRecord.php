<?php

declare(strict_types=1);

namespace App\Modules\DisciplinePerformance\Filament\Resources\DisciplinaryRecordResource\Pages;

use App\Modules\DisciplinePerformance\Filament\Resources\DisciplinaryRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditDisciplinaryRecord extends EditRecord
{
    protected static string $resource = DisciplinaryRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
