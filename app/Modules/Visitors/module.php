<?php

declare(strict_types=1);

use App\Modules\Visitors\Filament\Resources\VisitResource;

return [
    'id' => 'visitors',
    'name' => ['en' => 'Visitor Management'],
    'group' => 'Access',
    'icon' => 'heroicon-o-user-plus',
    'description' => [
        'en' => 'Pre-registration, walk-ins, watch-list checks, badge PDF, and check-in/out.',
    ],
    'features' => [
        'Visitor registry and visits',
        'Watch-list / blacklist checks',
        'Public/kiosk pre-registration',
        'Badge/pass PDF stub with QR',
    ],
    'depends_on' => ['clients_sites'],
    'conflicts_with' => [],
    'permissions' => [
        'visitors.view',
        'visitors.manage',
    ],
    'filament_resources' => [
        VisitResource::class,
    ],
    'api_scopes' => ['visitors.read', 'visitors.write'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'residential_society',
        'corporate_campus',
        'enterprise',
    ],
    'plan_tier' => 'starter',
    'price' => 0,
    'public_page' => [
        'slug' => 'visitor-management',
        'hero' => 'Register visitors, check watch-lists, print badges.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
