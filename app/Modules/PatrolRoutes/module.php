<?php

declare(strict_types=1);

use App\Modules\PatrolRoutes\Filament\Resources\PatrolRouteResource;

return [
    'id' => 'patrol_routes',
    'name' => ['en' => 'Patrol Routes & Tours'],
    'group' => 'Patrols',
    'icon' => 'heroicon-o-map',
    'description' => [
        'en' => 'Ordered or free-order patrol routes, schedules, and checkpoint sequences.',
    ],
    'features' => [
        'Patrol routes with ordered or free checkpoint order',
        'Route checkpoint sequences and dwell windows',
        'Tour schedules within shift windows',
        'Randomised-order option to reduce predictability',
    ],
    'depends_on' => ['checkpoints'],
    'conflicts_with' => [],
    'permissions' => [
        'patrol_routes.view',
        'patrol_routes.manage',
    ],
    'filament_resources' => [
        PatrolRouteResource::class,
    ],
    'api_scopes' => ['patrol_routes.read'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'small_guarding',
        'mall_retail',
        'enterprise',
    ],
    'plan_tier' => 'starter',
    'price' => 0,
    'public_page' => [
        'slug' => 'patrol-routes',
        'hero' => 'Design patrol routes and tour schedules for every site.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
