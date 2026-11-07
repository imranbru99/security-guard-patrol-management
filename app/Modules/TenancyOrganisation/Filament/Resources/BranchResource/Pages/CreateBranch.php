<?php

declare(strict_types=1);

namespace App\Modules\TenancyOrganisation\Filament\Resources\BranchResource\Pages;

use App\Modules\TenancyOrganisation\Filament\Resources\BranchResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateBranch extends CreateRecord
{
    protected static string $resource = BranchResource::class;
}
