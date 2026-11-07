<?php

declare(strict_types=1);

namespace App\Modules\PublicApiWebhooks\Filament\Resources\WebhookEndpointResource\Pages;

use App\Modules\PublicApiWebhooks\Filament\Resources\WebhookEndpointResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListWebhookEndpoints extends ListRecords
{
    protected static string $resource = WebhookEndpointResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
