<?php

declare(strict_types=1);

namespace App\Modules\TenancyOrganisation\Filament\Resources\BranchResource\Pages;

use App\Modules\TenancyOrganisation\Filament\Resources\BranchResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListBranches extends ListRecords
{
    protected static string $resource = BranchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
