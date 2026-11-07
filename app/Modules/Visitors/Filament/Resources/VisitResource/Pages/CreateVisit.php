<?php

declare(strict_types=1);

namespace App\Modules\Visitors\Filament\Resources\VisitResource\Pages;

use App\Modules\Visitors\Filament\Resources\VisitResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateVisit extends CreateRecord
{
    protected static string $resource = VisitResource::class;
}
