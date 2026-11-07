<?php

declare(strict_types=1);

use App\Modules\Dar\Filament\Resources\DarResource;

return [
    'id' => 'dar',
    'name' => ['en' => 'Daily Activity Reports (DAR)'],
    'group' => 'Safety',
    'icon' => 'heroicon-o-clipboard-document-list',
    'description' => [
        'en' => 'Time-stamped shift activity entries with supervisor sign-off and compile-from-shift stub.',
    ],
    'features' => [
        'DAR header per shift/site/guard',
        'Time-stamped activity entries',
        'Compile-from-shift stub service',
        'Client-visible option and sign-off',
    ],
    'depends_on' => ['shift_scheduling'],
    'conflicts_with' => [],
    'permissions' => [
        'dar.view',
        'dar.manage',
    ],
    'filament_resources' => [
        DarResource::class,
    ],
    'api_scopes' => ['dar.read', 'dar.write'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'small_guarding',
        'enterprise',
    ],
    'plan_tier' => 'starter',
    'price' => 0,
    'public_page' => [
        'slug' => 'dar',
        'hero' => 'Daily activity reports compiled from the shift floor.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
