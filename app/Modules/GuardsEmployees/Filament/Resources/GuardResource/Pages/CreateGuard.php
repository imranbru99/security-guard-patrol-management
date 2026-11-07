<?php

declare(strict_types=1);

namespace App\Modules\GuardsEmployees\Filament\Resources\GuardResource\Pages;

use App\Modules\GuardsEmployees\Filament\Resources\GuardResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateGuard extends CreateRecord
{
    protected static string $resource = GuardResource::class;
}
