<?php

declare(strict_types=1);

namespace App\Modules\PatrolRoutes\Filament\Resources\PatrolRouteResource\Pages;

use App\Modules\PatrolRoutes\Filament\Resources\PatrolRouteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditPatrolRoute extends EditRecord
{
    protected static string $resource = PatrolRouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
