<?php

declare(strict_types=1);

namespace App\Modules\LicencesDocuments\Filament\Resources\LicenceTypeResource\Pages;

use App\Modules\LicencesDocuments\Filament\Resources\LicenceTypeResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateLicenceType extends CreateRecord
{
    protected static string $resource = LicenceTypeResource::class;
}
