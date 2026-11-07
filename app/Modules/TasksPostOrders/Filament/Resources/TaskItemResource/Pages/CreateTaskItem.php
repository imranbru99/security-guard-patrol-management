<?php

declare(strict_types=1);

namespace App\Modules\TasksPostOrders\Filament\Resources\TaskItemResource\Pages;

use App\Modules\TasksPostOrders\Filament\Resources\TaskItemResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateTaskItem extends CreateRecord
{
    protected static string $resource = TaskItemResource::class;
}
