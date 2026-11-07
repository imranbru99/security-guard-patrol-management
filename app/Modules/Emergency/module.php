<?php

declare(strict_types=1);

use App\Modules\Emergency\Filament\Resources\EmergencyPlanResource;

return [
    'id' => 'emergency',
    'name' => ['en' => 'Emergency, Drills & Mustering'],
    'group' => 'Safety',
    'icon' => 'heroicon-o-fire',
    'description' => [
        'en' => 'Site emergency plans, drill scheduling, and muster roll-call records.',
    ],
    'features' => [
        'Emergency plans per site',
        'Drill scheduling and results',
        'Muster point roll-call',
    ],
    'depends_on' => ['clients_sites'],
    'conflicts_with' => [],
    'permissions' => [
        'emergency.view',
        'emergency.manage',
    ],
    'filament_resources' => [
        EmergencyPlanResource::class,
    ],
    'api_scopes' => ['emergency.read', 'emergency.write'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'industrial',
        'enterprise',
    ],
    'plan_tier' => 'enterprise',
    'price' => 0,
    'public_page' => [
        'slug' => 'emergency',
        'hero' => 'Plans, drills, and muster records when every second counts.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
