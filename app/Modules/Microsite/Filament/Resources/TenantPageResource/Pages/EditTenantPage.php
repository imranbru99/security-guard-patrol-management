<?php

declare(strict_types=1);

namespace App\Modules\Microsite\Filament\Resources\TenantPageResource\Pages;

use App\Modules\Microsite\Filament\Resources\TenantPageResource;
use Filament\Resources\Pages\EditRecord;

final class EditTenantPage extends EditRecord
{
    protected static string $resource = TenantPageResource::class;
}
