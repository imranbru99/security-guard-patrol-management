<?php

declare(strict_types=1);

namespace App\Modules\DisciplinePerformance\Filament\Resources\PerformanceScoreResource\Pages;

use App\Modules\DisciplinePerformance\Filament\Resources\PerformanceScoreResource;
use Filament\Resources\Pages\CreateRecord;

final class CreatePerformanceScore extends CreateRecord
{
    protected static string $resource = PerformanceScoreResource::class;
}
