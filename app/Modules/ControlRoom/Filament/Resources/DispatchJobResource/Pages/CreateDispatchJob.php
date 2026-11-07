<?php

declare(strict_types=1);

namespace App\Modules\ControlRoom\Filament\Resources\DispatchJobResource\Pages;

use App\Modules\ControlRoom\Filament\Resources\DispatchJobResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateDispatchJob extends CreateRecord
{
    protected static string $resource = DispatchJobResource::class;
}
