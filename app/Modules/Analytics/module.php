<?php

declare(strict_types=1);

use App\Modules\Analytics\Filament\Pages\AnalyticsDashboard;

return [
    'id' => 'analytics',
    'name' => ['en' => 'Analytics & KPIs'],
    'group' => 'Commerce',
    'icon' => 'heroicon-o-presentation-chart-line',
    'description' => [
        'en' => 'Executive KPI snapshots computed from operational database counts.',
    ],
    'features' => [
        'KpiSnapshot model',
        'AnalyticsDashboard with computed KPIs',
    ],
    'depends_on' => [],
    'conflicts_with' => [],
    'permissions' => [
        'analytics.view',
        'analytics.manage',
    ],
    'filament_resources' => [
        AnalyticsDashboard::class,
    ],
    'api_scopes' => ['analytics.read'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'enterprise',
    ],
    'plan_tier' => 'pro',
    'price' => 0,
    'public_page' => [
        'slug' => 'analytics-kpis',
        'hero' => 'Executive KPIs from live operational counts.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
