<?php

declare(strict_types=1);

namespace App\Modules\NotificationsMessaging\Filament\Resources\NotificationRuleResource\Pages;

use App\Modules\NotificationsMessaging\Filament\Resources\NotificationRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListNotificationRules extends ListRecords
{
    protected static string $resource = NotificationRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
