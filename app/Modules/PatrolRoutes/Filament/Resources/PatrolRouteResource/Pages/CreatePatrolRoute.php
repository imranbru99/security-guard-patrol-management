<?php

declare(strict_types=1);

namespace App\Modules\PatrolRoutes\Filament\Resources\PatrolRouteResource\Pages;

use App\Modules\PatrolRoutes\Filament\Resources\PatrolRouteResource;
use Filament\Resources\Pages\CreateRecord;

final class CreatePatrolRoute extends CreateRecord
{
    protected static string $resource = PatrolRouteResource::class;
}
