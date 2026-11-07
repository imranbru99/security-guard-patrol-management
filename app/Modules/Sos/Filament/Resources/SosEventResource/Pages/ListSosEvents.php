<?php

declare(strict_types=1);

namespace App\Modules\Sos\Filament\Resources\SosEventResource\Pages;

use App\Modules\Sos\Filament\Resources\SosEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListSosEvents extends ListRecords
{
    protected static string $resource = SosEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
