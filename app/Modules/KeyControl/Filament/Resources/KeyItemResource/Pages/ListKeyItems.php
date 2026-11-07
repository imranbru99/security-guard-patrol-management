<?php

declare(strict_types=1);

namespace App\Modules\KeyControl\Filament\Resources\KeyItemResource\Pages;

use App\Modules\KeyControl\Filament\Resources\KeyItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListKeyItems extends ListRecords
{
    protected static string $resource = KeyItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
