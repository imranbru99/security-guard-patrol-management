<?php

declare(strict_types=1);

namespace App\Modules\SettingsForms\Filament\Resources\FormTemplateResource\Pages;

use App\Modules\SettingsForms\Filament\Resources\FormTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditFormTemplate extends EditRecord
{
    protected static string $resource = FormTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
