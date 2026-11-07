<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filament\Resources\CatalogItemResource\Pages;

use App\Modules\Catalog\Filament\Resources\CatalogItemResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateCatalogItem extends CreateRecord
{
    protected static string $resource = CatalogItemResource::class;
}
