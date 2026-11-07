<?php

declare(strict_types=1);

use App\Modules\TrainingCertification\Filament\Resources\CourseResource;

return [
    'id' => 'training',
    'name' => ['en' => 'Training & Certification'],
    'group' => 'People',
    'icon' => 'heroicon-o-academic-cap',
    'description' => [
        'en' => 'Courses, sessions, enrolments, and certificates for guard upskilling.',
    ],
    'features' => [
        'Course catalogue',
        'Course sessions',
        'Enrolments',
        'Certificates',
        'Training API list stub',
    ],
    'depends_on' => ['guards'],
    'conflicts_with' => [],
    'permissions' => [
        'training.view',
        'training.manage',
    ],
    'filament_resources' => [
        CourseResource::class,
    ],
    'api_scopes' => ['training.read'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'corporate_campus',
        'industrial',
        'enterprise',
    ],
    'plan_tier' => 'pro',
    'price' => 0,
    'public_page' => [
        'slug' => 'training-certification',
        'hero' => 'Train and certify your guard workforce.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
