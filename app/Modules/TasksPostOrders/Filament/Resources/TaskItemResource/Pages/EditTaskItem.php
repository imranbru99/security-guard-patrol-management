<?php

declare(strict_types=1);

namespace App\Modules\TasksPostOrders\Filament\Resources\TaskItemResource\Pages;

use App\Modules\TasksPostOrders\Filament\Resources\TaskItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditTaskItem extends EditRecord
{
    protected static string $resource = TaskItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
