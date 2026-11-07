<?php

declare(strict_types=1);

namespace App\Modules\PayrollExport\Filament\Resources\PayrollRunResource\Pages;

use App\Modules\PayrollExport\Filament\Resources\PayrollRunResource;
use Filament\Resources\Pages\CreateRecord;

final class CreatePayrollRun extends CreateRecord
{
    protected static string $resource = PayrollRunResource::class;
}
