<?php

declare(strict_types=1);

use App\Modules\PatrolMonitoring\Filament\Pages\PatrolMonitoringPage;

return [
    'id' => 'patrol_monitoring',
    'name' => ['en' => 'Patrol Monitoring & Alerts'],
    'group' => 'Patrols',
    'icon' => 'heroicon-o-bell-alert',
    'description' => [
        'en' => 'Live tour progress, missed checkpoint detection, and patrol compliance alerts.',
    ],
    'features' => [
        'Missed checkpoint evaluator with grace periods',
        'Idempotent patrol event creation',
        'Supervisor monitoring page',
        'Tour compliance visibility',
    ],
    'depends_on' => ['patrol_execution'],
    'conflicts_with' => [],
    'permissions' => [
        'patrol_monitoring.view',
        'patrol_monitoring.manage',
    ],
    'filament_resources' => [
        PatrolMonitoringPage::class,
    ],
    'api_scopes' => ['patrol_monitoring.read'],
    'scheduled_jobs' => ['patrols:evaluate-missed'],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'enterprise',
    ],
    'plan_tier' => 'pro',
    'price' => 0,
    'public_page' => [
        'slug' => 'patrol-monitoring',
        'hero' => 'Catch missed checkpoints before clients do.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
