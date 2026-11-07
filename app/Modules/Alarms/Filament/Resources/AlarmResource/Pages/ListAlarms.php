<?php

declare(strict_types=1);

namespace App\Modules\Alarms\Filament\Resources\AlarmResource\Pages;

use App\Modules\Alarms\Filament\Resources\AlarmResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListAlarms extends ListRecords
{
    protected static string $resource = AlarmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
