<?php

declare(strict_types=1);

namespace App\Modules\TenancyOrganisation\Filament\Resources\DepartmentResource\Pages;

use App\Modules\TenancyOrganisation\Filament\Resources\DepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListDepartments extends ListRecords
{
    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
