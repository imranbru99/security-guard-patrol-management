<?php

declare(strict_types=1);

namespace App\Modules\Inspections\Filament\Resources\InspectionResource\Pages;

use App\Modules\Inspections\Filament\Resources\InspectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListInspections extends ListRecords
{
    protected static string $resource = InspectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
