<?php

declare(strict_types=1);

use App\Modules\Assets\Filament\Resources\AssetResource;

return [
    'id' => 'assets',
    'name' => ['en' => 'Assets & Equipment Inventory'],
    'group' => 'Access',
    'icon' => 'heroicon-o-cube',
    'description' => [
        'en' => 'Radios, vehicles, uniforms, armed equipment custody, maintenance, and stock.',
    ],
    'features' => [
        'Asset registry with QR labels',
        'Assignments to guards/posts',
        'Maintenance schedules',
        'Armed equipment custody chain fields',
        'Stock items and movements with reorder levels',
    ],
    'depends_on' => ['clients_sites'],
    'conflicts_with' => [],
    'permissions' => [
        'assets.view',
        'assets.manage',
    ],
    'filament_resources' => [
        AssetResource::class,
    ],
    'api_scopes' => ['assets.read', 'assets.write'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'corporate_campus',
        'industrial',
        'cash_in_transit',
        'enterprise',
    ],
    'plan_tier' => 'pro',
    'price' => 0,
    'public_page' => [
        'slug' => 'assets-equipment',
        'hero' => 'Track every radio, torch, and armed asset.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
