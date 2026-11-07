<?php

declare(strict_types=1);

namespace App\Modules\KeyControl\Filament\Resources\KeyItemResource\Pages;

use App\Modules\KeyControl\Filament\Resources\KeyItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditKeyItem extends EditRecord
{
    protected static string $resource = KeyItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
