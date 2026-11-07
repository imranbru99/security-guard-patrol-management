<?php

declare(strict_types=1);

namespace App\Modules\ControlRoom\Filament\Resources\DispatchJobResource\Pages;

use App\Modules\ControlRoom\Filament\Resources\DispatchJobResource;
use Filament\Resources\Pages\EditRecord;

final class EditDispatchJob extends EditRecord
{
    protected static string $resource = DispatchJobResource::class;
}
