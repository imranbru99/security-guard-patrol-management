<?php

declare(strict_types=1);

namespace App\Modules\LicencesDocuments\Filament\Resources\LicenceTypeResource\Pages;

use App\Modules\LicencesDocuments\Filament\Resources\LicenceTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListLicenceTypes extends ListRecords
{
    protected static string $resource = LicenceTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
