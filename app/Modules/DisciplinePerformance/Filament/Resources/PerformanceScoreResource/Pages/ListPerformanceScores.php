<?php

declare(strict_types=1);

namespace App\Modules\DisciplinePerformance\Filament\Resources\PerformanceScoreResource\Pages;

use App\Modules\DisciplinePerformance\Filament\Resources\PerformanceScoreResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListPerformanceScores extends ListRecords
{
    protected static string $resource = PerformanceScoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
