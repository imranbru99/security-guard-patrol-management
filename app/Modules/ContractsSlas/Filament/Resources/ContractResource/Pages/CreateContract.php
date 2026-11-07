<?php

declare(strict_types=1);

namespace App\Modules\ContractsSlas\Filament\Resources\ContractResource\Pages;

use App\Modules\ContractsSlas\Filament\Resources\ContractResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateContract extends CreateRecord
{
    protected static string $resource = ContractResource::class;
}
