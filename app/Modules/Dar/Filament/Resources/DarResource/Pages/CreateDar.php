<?php

declare(strict_types=1);

namespace App\Modules\Dar\Filament\Resources\DarResource\Pages;

use App\Modules\Dar\Filament\Resources\DarResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateDar extends CreateRecord
{
    protected static string $resource = DarResource::class;
}
