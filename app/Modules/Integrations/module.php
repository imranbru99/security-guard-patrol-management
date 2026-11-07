<?php

declare(strict_types=1);

use App\Modules\Integrations\Filament\Pages\IntegrationsHubPage;

return [
    'id' => 'integrations',
    'name' => ['en' => 'Integrations Hub'],
    'group' => 'Commerce',
    'icon' => 'heroicon-o-puzzle-piece',
    'description' => [
        'en' => 'External connections, fake SMS driver, and ICS feed for shifts.',
    ],
    'features' => [
        'IntegrationConnection model',
        'FakeSmsDriver',
        'ICS feed for shifts',
        'Filament IntegrationsHubPage',
    ],
    'depends_on' => [],
    'conflicts_with' => [],
    'permissions' => [
        'integrations.view',
        'integrations.manage',
    ],
    'filament_resources' => [
        IntegrationsHubPage::class,
    ],
    'api_scopes' => ['integrations.read'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'enterprise',
    ],
    'plan_tier' => 'pro',
    'price' => 0,
    'public_page' => [
        'slug' => 'integrations-hub',
        'hero' => 'Connect SMS, calendars, and third-party systems.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
