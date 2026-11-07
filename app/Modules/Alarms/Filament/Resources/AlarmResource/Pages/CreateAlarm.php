<?php

declare(strict_types=1);

namespace App\Modules\Alarms\Filament\Resources\AlarmResource\Pages;

use App\Modules\Alarms\Filament\Resources\AlarmResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateAlarm extends CreateRecord
{
    protected static string $resource = AlarmResource::class;
}
