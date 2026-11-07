<?php

declare(strict_types=1);

use App\Modules\PatrolExecution\Filament\Pages\MyPatrolPage;

return [
    'id' => 'patrol_execution',
    'name' => ['en' => 'Patrol Execution'],
    'group' => 'Patrols',
    'icon' => 'heroicon-o-play',
    'description' => [
        'en' => 'Guard PWA patrol tours with offline-idempotent scans and anti-cheat validation.',
    ],
    'features' => [
        'Start and complete patrol tours',
        'Idempotent offline scan sync via client_uuid',
        'Anti-cheat: GPS radius, sequence, mock-location flags',
        'Guard panel My Patrol page',
        'POST /api/v1/patrol/scan',
    ],
    'depends_on' => ['patrol_routes'],
    'conflicts_with' => [],
    'permissions' => [
        'patrol_execution.view',
        'patrol_execution.scan',
    ],
    'filament_resources' => [
        MyPatrolPage::class,
    ],
    'api_scopes' => ['patrol_execution.scan'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'small_guarding',
        'residential_society',
        'mall_retail',
        'enterprise',
    ],
    'plan_tier' => 'starter',
    'price' => 0,
    'public_page' => [
        'slug' => 'patrol-execution',
        'hero' => 'Guards run tours with anti-cheat checkpoint scans.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
