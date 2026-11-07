<?php

declare(strict_types=1);

namespace App\Modules\ControlRoom\Filament\Resources\DispatchJobResource\Pages;

use App\Modules\ControlRoom\Filament\Resources\DispatchJobResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListDispatchJobs extends ListRecords
{
    protected static string $resource = DispatchJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
