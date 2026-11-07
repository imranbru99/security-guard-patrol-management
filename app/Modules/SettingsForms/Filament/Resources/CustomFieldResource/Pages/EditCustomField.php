<?php

declare(strict_types=1);

namespace App\Modules\SettingsForms\Filament\Resources\CustomFieldResource\Pages;

use App\Modules\SettingsForms\Filament\Resources\CustomFieldResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditCustomField extends EditRecord
{
    protected static string $resource = CustomFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
