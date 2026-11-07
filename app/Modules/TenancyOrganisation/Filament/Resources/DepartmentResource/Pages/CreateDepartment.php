<?php

declare(strict_types=1);

namespace App\Modules\TenancyOrganisation\Filament\Resources\DepartmentResource\Pages;

use App\Modules\TenancyOrganisation\Filament\Resources\DepartmentResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateDepartment extends CreateRecord
{
    protected static string $resource = DepartmentResource::class;
}
