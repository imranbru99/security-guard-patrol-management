<?php

declare(strict_types=1);

namespace App\Modules\Dar\Filament\Resources\DarResource\Pages;

use App\Modules\Dar\Filament\Resources\DarResource;
use Filament\Resources\Pages\EditRecord;

final class EditDar extends EditRecord
{
    protected static string $resource = DarResource::class;
}
