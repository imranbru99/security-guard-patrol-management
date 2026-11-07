<?php

declare(strict_types=1);

namespace App\Modules\SettingsForms\Filament\Resources\FormTemplateResource\Pages;

use App\Modules\SettingsForms\Filament\Resources\FormTemplateResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateFormTemplate extends CreateRecord
{
    protected static string $resource = FormTemplateResource::class;
}
