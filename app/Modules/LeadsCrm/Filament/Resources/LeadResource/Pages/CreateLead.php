<?php

declare(strict_types=1);

namespace App\Modules\LeadsCrm\Filament\Resources\LeadResource\Pages;

use App\Modules\LeadsCrm\Filament\Resources\LeadResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateLead extends CreateRecord
{
    protected static string $resource = LeadResource::class;
}
