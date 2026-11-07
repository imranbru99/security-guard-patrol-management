<?php

declare(strict_types=1);

namespace App\Modules\SettingsForms\Filament\Resources\CustomFieldResource\Pages;

use App\Modules\SettingsForms\Filament\Resources\CustomFieldResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateCustomField extends CreateRecord
{
    protected static string $resource = CustomFieldResource::class;
}
