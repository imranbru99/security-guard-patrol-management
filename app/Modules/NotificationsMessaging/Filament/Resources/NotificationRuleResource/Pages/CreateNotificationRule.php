<?php

declare(strict_types=1);

namespace App\Modules\NotificationsMessaging\Filament\Resources\NotificationRuleResource\Pages;

use App\Modules\NotificationsMessaging\Filament\Resources\NotificationRuleResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateNotificationRule extends CreateRecord
{
    protected static string $resource = NotificationRuleResource::class;
}
