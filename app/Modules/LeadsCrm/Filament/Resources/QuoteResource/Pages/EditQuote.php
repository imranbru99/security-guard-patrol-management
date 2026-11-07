<?php

declare(strict_types=1);

namespace App\Modules\LeadsCrm\Filament\Resources\QuoteResource\Pages;

use App\Modules\LeadsCrm\Filament\Resources\QuoteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditQuote extends EditRecord
{
    protected static string $resource = QuoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
