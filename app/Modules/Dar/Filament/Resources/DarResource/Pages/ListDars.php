<?php

declare(strict_types=1);

namespace App\Modules\Dar\Filament\Resources\DarResource\Pages;

use App\Modules\Dar\Filament\Resources\DarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListDars extends ListRecords
{
    protected static string $resource = DarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
