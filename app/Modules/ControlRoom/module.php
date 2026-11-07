<?php

declare(strict_types=1);

use App\Modules\ControlRoom\Filament\Pages\AlertQueuePage;
use App\Modules\ControlRoom\Filament\Pages\LiveWallPage;
use App\Modules\ControlRoom\Filament\Resources\DispatchJobResource;

return [
    'id' => 'control_room',
    'name' => ['en' => 'Control Room & Dispatch'],
    'group' => 'Safety',
    'icon' => 'heroicon-o-tv',
    'description' => [
        'en' => 'Live wall, alert queue, and dispatch jobs for SOS, patrol, and alarm events.',
    ],
    'features' => [
        'Live wall at /control',
        'Alert queue',
        'Dispatch jobs and radio/phone logs',
        'Acknowledge and assign workflow',
    ],
    'depends_on' => ['sos'],
    'conflicts_with' => [],
    'permissions' => [
        'control_room.view',
        'control_room.dispatch',
    ],
    'filament_resources' => [
        LiveWallPage::class,
        AlertQueuePage::class,
        DispatchJobResource::class,
    ],
    'api_scopes' => ['control_room.read', 'control_room.dispatch'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'remote_monitoring',
        'enterprise',
    ],
    'plan_tier' => 'pro',
    'price' => 0,
    'public_page' => [
        'slug' => 'control-room',
        'hero' => 'One wall for SOS, missed patrols, geofences, and alarms.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
