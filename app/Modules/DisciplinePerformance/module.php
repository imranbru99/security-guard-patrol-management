<?php

declare(strict_types=1);

use App\Modules\DisciplinePerformance\Filament\Resources\DisciplinaryRecordResource;
use App\Modules\DisciplinePerformance\Filament\Resources\PerformanceScoreResource;

return [
    'id' => 'discipline',
    'name' => ['en' => 'Discipline, Performance & Rewards'],
    'group' => 'People',
    'icon' => 'heroicon-o-scale',
    'description' => [
        'en' => 'Disciplinary records and performance scores for guard workforce management.',
    ],
    'features' => [
        'Disciplinary records',
        'Performance scores',
        'Discipline API list stub',
    ],
    'depends_on' => ['guards'],
    'conflicts_with' => [],
    'permissions' => [
        'discipline.view',
        'discipline.manage',
    ],
    'filament_resources' => [
        DisciplinaryRecordResource::class,
        PerformanceScoreResource::class,
    ],
    'api_scopes' => ['discipline.read'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'corporate_campus',
        'enterprise',
    ],
    'plan_tier' => 'pro',
    'price' => 0,
    'public_page' => [
        'slug' => 'discipline-performance',
        'hero' => 'Track discipline and performance fairly across sites.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
