<?php

declare(strict_types=1);

namespace App\Modules\KeyControl\Filament\Resources\KeyItemResource\Pages;

use App\Modules\KeyControl\Filament\Resources\KeyItemResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateKeyItem extends CreateRecord
{
    protected static string $resource = KeyItemResource::class;
}
