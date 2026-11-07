<?php

declare(strict_types=1);

namespace App\Modules\Assets\Filament\Resources\AssetResource\Pages;

use App\Modules\Assets\Filament\Resources\AssetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditAsset extends EditRecord
{
    protected static string $resource = AssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
