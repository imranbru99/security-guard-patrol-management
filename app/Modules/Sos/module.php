<?php

declare(strict_types=1);

use App\Modules\Sos\Filament\Pages\SosControlRoomPage;
use App\Modules\Sos\Filament\Resources\SosEventResource;

return [
    'id' => 'sos',
    'name' => ['en' => 'SOS, Panic & Lone-Worker Safety'],
    'group' => 'Safety',
    'icon' => 'heroicon-o-bell-alert',
    'description' => [
        'en' => 'One-tap SOS, silent panic, check-in timers, and control-room acknowledgement.',
    ],
    'features' => [
        'SOS trigger API',
        'Silent and drill modes',
        'Lone-worker check-in timers',
        'Control room SOS list',
    ],
    'depends_on' => ['guards'],
    'conflicts_with' => [],
    'permissions' => [
        'sos.view',
        'sos.trigger',
        'sos.manage',
    ],
    'filament_resources' => [
        SosEventResource::class,
        SosControlRoomPage::class,
    ],
    'api_scopes' => ['sos.trigger', 'sos.read'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'residential_society',
        'cash_in_transit',
        'remote_monitoring',
        'enterprise',
    ],
    'plan_tier' => 'pro',
    'price' => 0,
    'public_page' => [
        'slug' => 'sos',
        'hero' => 'Panic button and lone-worker timers that reach the control room fast.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
