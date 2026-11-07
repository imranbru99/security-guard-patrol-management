<?php

declare(strict_types=1);

namespace App\Modules\LicencesDocuments\Filament\Resources\GuardLicenceResource\Pages;

use App\Modules\LicencesDocuments\Filament\Resources\GuardLicenceResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

final class ListGuardLicences extends ListRecords
{
    protected static string $resource = GuardLicenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function mount(): void
    {
        parent::mount();

        Notification::make()
            ->title(__('LicencesDocuments::messages.expiry_alert_note'))
            ->warning()
            ->persistent()
            ->send();
    }
}
