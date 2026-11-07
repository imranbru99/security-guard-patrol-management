<?php

declare(strict_types=1);

namespace App\Modules\DisciplinePerformance\Filament\Resources\PerformanceScoreResource\Pages;

use App\Modules\DisciplinePerformance\Filament\Resources\PerformanceScoreResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditPerformanceScore extends EditRecord
{
    protected static string $resource = PerformanceScoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
