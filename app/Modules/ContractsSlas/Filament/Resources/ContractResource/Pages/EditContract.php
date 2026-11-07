<?php

declare(strict_types=1);

namespace App\Modules\ContractsSlas\Filament\Resources\ContractResource\Pages;

use App\Modules\ContractsSlas\Filament\Resources\ContractResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditContract extends EditRecord
{
    protected static string $resource = ContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
