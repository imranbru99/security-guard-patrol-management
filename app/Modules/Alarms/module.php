<?php

declare(strict_types=1);

use App\Modules\Alarms\Filament\Resources\AlarmResource;

return [
    'id' => 'alarms',
    'name' => ['en' => 'Alarm & Event Response'],
    'group' => 'Safety',
    'icon' => 'heroicon-o-speaker-wave',
    'description' => [
        'en' => 'Inbound alarm webhooks with response SLA tracking and outcome codes.',
    ],
    'features' => [
        'Inbound alarm webhook API',
        'Response SLA fields and breach flags',
        'Outcome codes and false-alarm tracking',
        'Optional dispatch job linkage',
    ],
    'depends_on' => ['control_room'],
    'conflicts_with' => [],
    'permissions' => [
        'alarms.view',
        'alarms.manage',
        'alarms.ingest',
    ],
    'filament_resources' => [
        AlarmResource::class,
    ],
    'api_scopes' => ['alarms.read', 'alarms.ingest'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'mall_retail',
        'remote_monitoring',
        'enterprise',
    ],
    'plan_tier' => 'enterprise',
    'price' => 0,
    'public_page' => [
        'slug' => 'alarms',
        'hero' => 'Ingest panel alarms and measure response against SLA.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
