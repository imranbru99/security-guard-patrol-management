<?php

declare(strict_types=1);

namespace App\Modules\CheckpointsTags\Filament\Resources\CheckpointResource\Pages;

use App\Modules\CheckpointsTags\Filament\Resources\CheckpointResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListCheckpoints extends ListRecords
{
    protected static string $resource = CheckpointResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
