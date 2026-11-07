<?php

declare(strict_types=1);

namespace App\Modules\GuardsEmployees\Filament\Resources\GuardResource\Pages;

use App\Modules\GuardsEmployees\Filament\Resources\GuardResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditGuard extends EditRecord
{
    protected static string $resource = GuardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
