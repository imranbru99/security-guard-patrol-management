<?php

declare(strict_types=1);

use App\Modules\KeyControl\Filament\Resources\KeyItemResource;

return [
    'id' => 'key_control',
    'name' => ['en' => 'Key Control'],
    'group' => 'Access',
    'icon' => 'heroicon-o-key',
    'description' => [
        'en' => 'Key registry with issue/return logs, overdue flags, and lost-key workflow.',
    ],
    'features' => [
        'Key registry per site/post',
        'Issue and return log with signatures',
        'Overdue detection helper',
        'Lost key status workflow',
    ],
    'depends_on' => ['clients_sites'],
    'conflicts_with' => [],
    'permissions' => [
        'key_control.view',
        'key_control.manage',
    ],
    'filament_resources' => [
        KeyItemResource::class,
    ],
    'api_scopes' => ['key_control.read', 'key_control.write'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'corporate_campus',
        'enterprise',
    ],
    'plan_tier' => 'starter',
    'price' => 0,
    'public_page' => [
        'slug' => 'key-control',
        'hero' => 'Track every key issue and return.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
