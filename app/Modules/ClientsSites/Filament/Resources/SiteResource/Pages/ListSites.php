<?php

declare(strict_types=1);

namespace App\Modules\ClientsSites\Filament\Resources\SiteResource\Pages;

use App\Modules\ClientsSites\Filament\Resources\SiteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListSites extends ListRecords
{
    protected static string $resource = SiteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
