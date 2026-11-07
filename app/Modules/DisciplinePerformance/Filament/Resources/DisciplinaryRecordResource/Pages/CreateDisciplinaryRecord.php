<?php

declare(strict_types=1);

namespace App\Modules\DisciplinePerformance\Filament\Resources\DisciplinaryRecordResource\Pages;

use App\Modules\DisciplinePerformance\Filament\Resources\DisciplinaryRecordResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateDisciplinaryRecord extends CreateRecord
{
    protected static string $resource = DisciplinaryRecordResource::class;
}
