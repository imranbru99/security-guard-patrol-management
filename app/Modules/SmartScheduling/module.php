<?php

declare(strict_types=1);

use App\Modules\SmartScheduling\Filament\Pages\SmartSchedulingPage;

return [
    'id' => 'smart_scheduling',
    'name' => ['en' => 'Smart Scheduling & Forecasting'],
    'group' => 'Commerce',
    'icon' => 'heroicon-o-chart-bar-square',
    'description' => [
        'en' => 'Coverage-gap prediction and demand forecasting from historical shift counts.',
    ],
    'features' => [
        'ForecastRun model',
        'CoverageGapPredictor from history counts',
        'Filament SmartSchedulingPage',
    ],
    'depends_on' => ['shift_scheduling'],
    'conflicts_with' => [],
    'permissions' => [
        'smart_scheduling.view',
        'smart_scheduling.manage',
    ],
    'filament_resources' => [
        SmartSchedulingPage::class,
    ],
    'api_scopes' => ['smart_scheduling.read'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'enterprise',
    ],
    'plan_tier' => 'addon',
    'price' => 0,
    'public_page' => [
        'slug' => 'smart-scheduling',
        'hero' => 'Predict coverage gaps from roster history.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
