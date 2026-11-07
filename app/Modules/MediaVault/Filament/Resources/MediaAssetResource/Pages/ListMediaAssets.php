<?php

declare(strict_types=1);

namespace App\Modules\MediaVault\Filament\Resources\MediaAssetResource\Pages;

use App\Modules\MediaVault\Filament\Resources\MediaAssetResource;
use Filament\Resources\Pages\ListRecords;

final class ListMediaAssets extends ListRecords
{
    protected static string $resource = MediaAssetResource::class;
}
