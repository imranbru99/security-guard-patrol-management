<?php

declare(strict_types=1);

use App\Modules\GuardsEmployees\Filament\Resources\GuardResource;

return [
    'id' => 'guards',
    'name' => ['en' => 'Guards & Employees'],
    'group' => 'People',
    'icon' => 'heroicon-o-user-group',
    'description' => [
        'en' => 'Guard roster, badges, skills, and emergency contacts for the tenant workforce.',
    ],
    'features' => [
        'Guard employee profiles',
        'Branch assignment',
        'Badge QR and photo path',
        'Skills and emergency contact JSON',
        'Guards API list stub',
    ],
    'depends_on' => [],
    'conflicts_with' => [],
    'permissions' => [
        'guards.view',
        'guards.manage',
    ],
    'filament_resources' => [
        GuardResource::class,
    ],
    'api_scopes' => ['guards.read'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'small_guarding',
        'residential_society',
        'corporate_campus',
        'mall_retail',
        'industrial',
        'event_security',
        'cash_in_transit',
        'remote_monitoring',
        'enterprise',
    ],
    'plan_tier' => 'starter',
    'price' => 0,
    'public_page' => [
        'slug' => 'guards-employees',
        'hero' => 'Manage your guard workforce in one roster.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
