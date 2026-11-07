<?php

declare(strict_types=1);

use App\Modules\LeaveAvailability\Filament\Resources\LeaveRequestResource;

return [
    'id' => 'leave',
    'name' => ['en' => 'Leave, Availability & Time-off'],
    'group' => 'People',
    'icon' => 'heroicon-o-calendar-days',
    'description' => [
        'en' => 'Leave types, leave requests, and guard availability windows.',
    ],
    'features' => [
        'Leave type catalogue',
        'Leave request workflow stub',
        'Availability windows',
        'Leave API list stub',
    ],
    'depends_on' => ['guards'],
    'conflicts_with' => [],
    'permissions' => [
        'leave.view',
        'leave.manage',
    ],
    'filament_resources' => [
        LeaveRequestResource::class,
    ],
    'api_scopes' => ['leave.read'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'small_guarding',
        'corporate_campus',
        'enterprise',
    ],
    'plan_tier' => 'starter',
    'price' => 0,
    'public_page' => [
        'slug' => 'leave-availability',
        'hero' => 'Track leave and availability across your roster.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
