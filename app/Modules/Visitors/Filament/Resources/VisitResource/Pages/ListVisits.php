<?php

declare(strict_types=1);

namespace App\Modules\Visitors\Filament\Resources\VisitResource\Pages;

use App\Modules\Visitors\Filament\Resources\VisitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListVisits extends ListRecords
{
    protected static string $resource = VisitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
