<?php

declare(strict_types=1);

namespace App\Modules\ClientsSites\Filament\Resources\ClientResource\Pages;

use App\Modules\ClientsSites\Filament\Resources\ClientResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListClients extends ListRecords
{
    protected static string $resource = ClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
