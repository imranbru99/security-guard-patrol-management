<?php

declare(strict_types=1);

use App\Modules\Hazards\Filament\Resources\HazardResource;

return [
    'id' => 'hazards',
    'name' => ['en' => 'Hazards, Maintenance & Safety Reports'],
    'group' => 'Safety',
    'icon' => 'heroicon-o-wrench-screwdriver',
    'description' => [
        'en' => 'Hazard and near-miss reports with assignment and status tracking.',
    ],
    'features' => [
        'Hazard / near-miss / defect reports',
        'Assignment to maintenance contacts',
        'Severity and status tracking',
    ],
    'depends_on' => ['clients_sites'],
    'conflicts_with' => [],
    'permissions' => [
        'hazards.view',
        'hazards.manage',
    ],
    'filament_resources' => [
        HazardResource::class,
    ],
    'api_scopes' => ['hazards.read', 'hazards.write'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'residential_society',
        'mall_retail',
        'industrial',
        'enterprise',
    ],
    'plan_tier' => 'pro',
    'price' => 0,
    'public_page' => [
        'slug' => 'hazards',
        'hero' => 'Capture hazards and facility defects before they become incidents.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
