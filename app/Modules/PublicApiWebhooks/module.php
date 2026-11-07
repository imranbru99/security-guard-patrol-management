<?php

declare(strict_types=1);

use App\Modules\PublicApiWebhooks\Filament\Resources\WebhookEndpointResource;

return [
    'id' => 'public_api',
    'name' => ['en' => 'Public API & Webhooks'],
    'group' => 'Platform Core',
    'icon' => 'heroicon-o-globe-alt',
    'description' => [
        'en' => 'Outbound webhooks with HMAC signatures, delivery logs, and API ping endpoints.',
    ],
    'features' => [
        'Webhook endpoints with event subscriptions',
        'HMAC X-GuardOps-Signature outbound dispatcher',
        'Delivery attempt logging',
        'Module-gated webhook test ping',
    ],
    'depends_on' => [],
    'conflicts_with' => [],
    'permissions' => [
        'public_api.view',
        'public_api.manage',
        'public_api.webhooks.manage',
    ],
    'filament_resources' => [
        WebhookEndpointResource::class,
    ],
    'api_scopes' => ['public_api.read', 'public_api.webhooks.write'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => ['enterprise'],
    'plan_tier' => 'starter',
    'price' => 0,
    'public_page' => [
        'slug' => 'public-api-webhooks',
        'hero' => 'Connect GuardOps to your stack with signed webhooks.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
