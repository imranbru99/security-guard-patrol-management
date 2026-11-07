<?php

declare(strict_types=1);

namespace App\Modules\NotificationsMessaging\Filament\Resources\NotificationTemplateResource\Pages;

use App\Modules\NotificationsMessaging\Filament\Resources\NotificationTemplateResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateNotificationTemplate extends CreateRecord
{
    protected static string $resource = NotificationTemplateResource::class;
}
