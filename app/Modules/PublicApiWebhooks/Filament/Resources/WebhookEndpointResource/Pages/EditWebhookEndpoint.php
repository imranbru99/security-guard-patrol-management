<?php

declare(strict_types=1);

namespace App\Modules\PublicApiWebhooks\Filament\Resources\WebhookEndpointResource\Pages;

use App\Modules\PublicApiWebhooks\Filament\Resources\WebhookEndpointResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

final class EditWebhookEndpoint extends EditRecord
{
    protected static string $resource = WebhookEndpointResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
