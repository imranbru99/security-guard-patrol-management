<?php

declare(strict_types=1);

namespace App\Modules\Assets\Filament\Resources\AssetResource\Pages;

use App\Modules\Assets\Filament\Resources\AssetResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateAsset extends CreateRecord
{
    protected static string $resource = AssetResource::class;
}
