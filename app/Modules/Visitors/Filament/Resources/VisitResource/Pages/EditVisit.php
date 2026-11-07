<?php

declare(strict_types=1);

namespace App\Modules\Visitors\Filament\Resources\VisitResource\Pages;

use App\Modules\Visitors\Filament\Resources\VisitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditVisit extends EditRecord
{
    protected static string $resource = VisitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
