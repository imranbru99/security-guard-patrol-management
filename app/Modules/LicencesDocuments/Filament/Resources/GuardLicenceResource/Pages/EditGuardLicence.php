<?php

declare(strict_types=1);

namespace App\Modules\LicencesDocuments\Filament\Resources\GuardLicenceResource\Pages;

use App\Modules\LicencesDocuments\Filament\Resources\GuardLicenceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditGuardLicence extends EditRecord
{
    protected static string $resource = GuardLicenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
