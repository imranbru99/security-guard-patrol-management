<?php

declare(strict_types=1);

use App\Modules\TasksPostOrders\Filament\Resources\TaskItemResource;

return [
    'id' => 'tasks_post_orders',
    'name' => ['en' => 'Tasks & Post Orders'],
    'group' => 'Access',
    'icon' => 'heroicon-o-clipboard-document-list',
    'description' => [
        'en' => 'Assignable tasks with due times, completions, and post-order acknowledgements.',
    ],
    'features' => [
        'Tasks assigned to guards or posts',
        'Recurring task flags and checklists',
        'Completion proof records',
        'Post-order ack via existing handover PostOrder',
    ],
    'depends_on' => ['clients_sites', 'handover'],
    'conflicts_with' => [],
    'permissions' => [
        'tasks_post_orders.view',
        'tasks_post_orders.manage',
    ],
    'filament_resources' => [
        TaskItemResource::class,
    ],
    'api_scopes' => ['tasks_post_orders.read', 'tasks_post_orders.write'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'mall_retail',
        'enterprise',
    ],
    'plan_tier' => 'starter',
    'price' => 0,
    'public_page' => [
        'slug' => 'tasks-post-orders',
        'hero' => 'Assign tasks and confirm post-order reads.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
