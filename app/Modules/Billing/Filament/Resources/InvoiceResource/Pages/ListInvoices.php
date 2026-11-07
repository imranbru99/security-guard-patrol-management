<?php

declare(strict_types=1);

namespace App\Modules\Billing\Filament\Resources\InvoiceResource\Pages;

use App\Modules\Billing\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListInvoices extends ListRecords
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
