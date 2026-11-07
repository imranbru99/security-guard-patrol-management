<?php

declare(strict_types=1);

namespace App\Modules\TenancyOrganisation\Filament\Resources\DepartmentResource\Pages;

use App\Modules\TenancyOrganisation\Filament\Resources\DepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditDepartment extends EditRecord
{
    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
