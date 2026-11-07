<?php

declare(strict_types=1);

namespace App\Modules\LeadsCrm\Filament\Resources\LeadResource\Pages;

use App\Modules\LeadsCrm\Filament\Resources\LeadResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditLead extends EditRecord
{
    protected static string $resource = LeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
