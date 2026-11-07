<?php

declare(strict_types=1);

namespace App\Modules\Billing\Filament\Resources\InvoiceResource\Pages;

use App\Modules\Billing\Filament\Resources\InvoiceResource;
use Filament\Resources\Pages\EditRecord;

final class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;
}
