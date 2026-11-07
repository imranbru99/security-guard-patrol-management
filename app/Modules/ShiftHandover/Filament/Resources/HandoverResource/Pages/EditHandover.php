<?php

declare(strict_types=1);

namespace App\Modules\ShiftHandover\Filament\Resources\HandoverResource\Pages;

use App\Modules\ShiftHandover\Filament\Resources\HandoverResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditHandover extends EditRecord
{
    protected static string $resource = HandoverResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
