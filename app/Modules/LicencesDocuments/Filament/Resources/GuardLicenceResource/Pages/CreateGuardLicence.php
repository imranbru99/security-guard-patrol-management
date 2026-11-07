<?php

declare(strict_types=1);

namespace App\Modules\LicencesDocuments\Filament\Resources\GuardLicenceResource\Pages;

use App\Modules\LicencesDocuments\Filament\Resources\GuardLicenceResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateGuardLicence extends CreateRecord
{
    protected static string $resource = GuardLicenceResource::class;
}
