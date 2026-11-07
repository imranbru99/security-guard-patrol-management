<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filament\Resources\CatalogItemResource\Pages;

use App\Modules\Catalog\Filament\Resources\CatalogItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListCatalogItems extends ListRecords
{
    protected static string $resource = CatalogItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
