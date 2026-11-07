<?php

declare(strict_types=1);

use App\Modules\RecruitmentAts\Filament\Resources\ApplicationResource;

return [
    'id' => 'recruitment',
    'name' => ['en' => 'Recruitment (ATS)'],
    'group' => 'People',
    'icon' => 'heroicon-o-briefcase',
    'description' => [
        'en' => 'Applicants and applications with a pipeline-stage ATS view. Public apply route reserved for tenant microsite later.',
    ],
    'features' => [
        'Applicant profiles',
        'Applications with pipeline stages',
        'Kanban-ish Filament table',
        'Gated tenant routes for now',
        'Recruitment API list stub',
    ],
    'depends_on' => [],
    'conflicts_with' => [],
    'permissions' => [
        'recruitment.view',
        'recruitment.manage',
    ],
    'filament_resources' => [
        ApplicationResource::class,
    ],
    'api_scopes' => ['recruitment.read'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'enterprise',
        'event_security',
    ],
    'plan_tier' => 'pro',
    'price' => 0,
    'public_page' => [
        'slug' => 'recruitment-ats',
        'hero' => 'Hire guards with a simple applicant pipeline.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
