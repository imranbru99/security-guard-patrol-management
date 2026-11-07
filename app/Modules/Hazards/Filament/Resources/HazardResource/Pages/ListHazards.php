<?php

declare(strict_types=1);

namespace App\Modules\Hazards\Filament\Resources\HazardResource\Pages;

use App\Modules\Hazards\Filament\Resources\HazardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListHazards extends ListRecords
{
    protected static string $resource = HazardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
