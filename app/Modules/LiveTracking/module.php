<?php

declare(strict_types=1);

use App\Modules\LiveTracking\Filament\Pages\LiveTrackingPage;

return [
    'id' => 'live_tracking',
    'name' => ['en' => 'Live Guard Tracking & Geofencing'],
    'group' => 'Patrols',
    'icon' => 'heroicon-o-signal',
    'description' => [
        'en' => 'Opt-in location during shift windows with geofence enter/exit events.',
    ],
    'features' => [
        'Tracking sessions bounded to shift windows',
        'Location ping API with privacy enforcement',
        'Geofences and geofence events',
        'Supervisor live tracking page',
    ],
    'depends_on' => ['patrol_execution'],
    'conflicts_with' => [],
    'permissions' => [
        'live_tracking.view',
        'live_tracking.ping',
    ],
    'filament_resources' => [
        LiveTrackingPage::class,
    ],
    'api_scopes' => ['live_tracking.ping'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'cash_in_transit',
        'enterprise',
    ],
    'plan_tier' => 'pro',
    'price' => 0,
    'public_page' => [
        'slug' => 'live-tracking',
        'hero' => 'Track guards only inside their shift window.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
