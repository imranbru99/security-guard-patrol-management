<?php

declare(strict_types=1);

namespace App\Modules\RecruitmentAts\Filament\Resources\ApplicationResource\Pages;

use App\Modules\RecruitmentAts\Filament\Resources\ApplicationResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateApplication extends CreateRecord
{
    protected static string $resource = ApplicationResource::class;
}
