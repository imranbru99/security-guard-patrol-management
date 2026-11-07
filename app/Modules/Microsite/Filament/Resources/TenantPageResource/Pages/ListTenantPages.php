<?php

declare(strict_types=1);

namespace App\Modules\Microsite\Filament\Resources\TenantPageResource\Pages;

use App\Modules\Microsite\Filament\Resources\TenantPageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListTenantPages extends ListRecords
{
    protected static string $resource = TenantPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
