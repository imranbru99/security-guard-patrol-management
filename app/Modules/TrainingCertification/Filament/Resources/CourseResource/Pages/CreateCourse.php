<?php

declare(strict_types=1);

namespace App\Modules\TrainingCertification\Filament\Resources\CourseResource\Pages;

use App\Modules\TrainingCertification\Filament\Resources\CourseResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateCourse extends CreateRecord
{
    protected static string $resource = CourseResource::class;
}
