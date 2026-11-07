<?php

declare(strict_types=1);

namespace App\Modules\LeadsCrm\Filament\Resources\LeadResource\Pages;

use App\Modules\LeadsCrm\Filament\Resources\LeadResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListLeads extends ListRecords
{
    protected static string $resource = LeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
