<?php

declare(strict_types=1);

namespace App\Modules\Microsite\Filament\Resources\TenantPageResource\Pages;

use App\Modules\Microsite\Filament\Resources\TenantPageResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateTenantPage extends CreateRecord
{
    protected static string $resource = TenantPageResource::class;
}
