<?php

declare(strict_types=1);

namespace App\Modules\CheckpointsTags\Filament\Resources\CheckpointResource\Pages;

use App\Modules\CheckpointsTags\Filament\Resources\CheckpointResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateCheckpoint extends CreateRecord
{
    protected static string $resource = CheckpointResource::class;
}
