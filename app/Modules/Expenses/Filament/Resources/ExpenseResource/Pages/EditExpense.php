<?php

declare(strict_types=1);

namespace App\Modules\Expenses\Filament\Resources\ExpenseResource\Pages;

use App\Modules\Expenses\Filament\Resources\ExpenseResource;
use Filament\Resources\Pages\EditRecord;

final class EditExpense extends EditRecord
{
    protected static string $resource = ExpenseResource::class;
}
