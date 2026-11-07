<?php

declare(strict_types=1);

namespace App\Modules\NotificationsMessaging\Filament\Resources\NotificationTemplateResource\Pages;

use App\Modules\NotificationsMessaging\Filament\Resources\NotificationTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditNotificationTemplate extends EditRecord
{
    protected static string $resource = NotificationTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
