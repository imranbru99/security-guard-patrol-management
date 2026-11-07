<?php

declare(strict_types=1);

namespace App\Modules\PayrollExport\Filament\Resources\PayrollRunResource\Pages;

use App\Modules\PayrollExport\Filament\Resources\PayrollRunResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListPayrollRuns extends ListRecords
{
    protected static string $resource = PayrollRunResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
