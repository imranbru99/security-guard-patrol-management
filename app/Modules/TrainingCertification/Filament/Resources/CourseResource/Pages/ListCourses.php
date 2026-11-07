<?php

declare(strict_types=1);

namespace App\Modules\TrainingCertification\Filament\Resources\CourseResource\Pages;

use App\Modules\TrainingCertification\Filament\Resources\CourseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListCourses extends ListRecords
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
