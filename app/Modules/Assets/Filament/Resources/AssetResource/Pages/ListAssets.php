<?php

declare(strict_types=1);

namespace App\Modules\Assets\Filament\Resources\AssetResource\Pages;

use App\Modules\Assets\Filament\Resources\AssetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListAssets extends ListRecords
{
    protected static string $resource = AssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
