<?php

declare(strict_types=1);

use App\Modules\MobilePwa\Filament\Pages\PwaStatusPage;

return [
    'id' => 'mobile_pwa',
    'name' => ['en' => 'Mobile (PWA) & Offline'],
    'group' => 'Commerce',
    'icon' => 'heroicon-o-device-phone-mobile',
    'description' => [
        'en' => 'Installable guard PWA with offline sync acknowledging client_uuid.',
    ],
    'features' => [
        'Web app manifest',
        'Service worker stub',
        'Guard PwaStatusPage',
        'Offline sync API',
    ],
    'depends_on' => [],
    'conflicts_with' => [],
    'permissions' => [
        'mobile_pwa.view',
        'mobile_pwa.sync',
    ],
    'filament_resources' => [
        PwaStatusPage::class,
    ],
    'api_scopes' => ['mobile_pwa.sync'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'small_guarding',
        'enterprise',
        'event_security',
    ],
    'plan_tier' => 'starter',
    'price' => 0,
    'public_page' => [
        'slug' => 'mobile-pwa',
        'hero' => 'Installable guard app with offline queue sync.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
