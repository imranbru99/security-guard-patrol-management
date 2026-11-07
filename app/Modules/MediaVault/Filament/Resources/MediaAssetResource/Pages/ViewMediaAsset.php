<?php

declare(strict_types=1);

namespace App\Modules\MediaVault\Filament\Resources\MediaAssetResource\Pages;

use App\Modules\MediaVault\Filament\Resources\MediaAssetResource;
use Filament\Resources\Pages\ViewRecord;

final class ViewMediaAsset extends ViewRecord
{
    protected static string $resource = MediaAssetResource::class;
}
