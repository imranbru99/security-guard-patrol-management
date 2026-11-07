<?php

declare(strict_types=1);

namespace App\Modules\PublicApiWebhooks\Filament\Resources\WebhookEndpointResource\Pages;

use App\Modules\PublicApiWebhooks\Filament\Resources\WebhookEndpointResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateWebhookEndpoint extends CreateRecord
{
    protected static string $resource = WebhookEndpointResource::class;
}
