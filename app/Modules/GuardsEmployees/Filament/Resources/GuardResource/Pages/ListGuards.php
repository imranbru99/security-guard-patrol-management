<?php

declare(strict_types=1);

namespace App\Modules\GuardsEmployees\Filament\Resources\GuardResource\Pages;

use App\Modules\GuardsEmployees\Filament\Resources\GuardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListGuards extends ListRecords
{
    protected static string $resource = GuardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
