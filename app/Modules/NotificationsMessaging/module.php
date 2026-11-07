<?php

declare(strict_types=1);

use App\Modules\NotificationsMessaging\Filament\Resources\NotificationRuleResource;
use App\Modules\NotificationsMessaging\Filament\Resources\NotificationTemplateResource;

return [
    'id' => 'notifications',
    'name' => ['en' => 'Notifications & Messaging'],
    'group' => 'Platform Core',
    'icon' => 'heroicon-o-bell',
    'description' => [
        'en' => 'Templates, rules, and multi-channel notification dispatch with pluggable drivers.',
    ],
    'features' => [
        'Notification templates',
        'Rules engine stub',
        'Log and fake channel drivers',
        'Dispatch service and event listener stub',
    ],
    'depends_on' => ['tenancy'],
    'conflicts_with' => [],
    'permissions' => [
        'notifications.view',
        'notifications.manage',
        'notifications.dispatch',
    ],
    'filament_resources' => [
        NotificationTemplateResource::class,
        NotificationRuleResource::class,
    ],
    'api_scopes' => ['notifications.read'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'small_guarding',
        'residential_society',
        'corporate_campus',
        'mall_retail',
        'industrial',
        'event_security',
        'cash_in_transit',
        'remote_monitoring',
        'enterprise',
    ],
    'plan_tier' => 'core',
    'price' => 0,
    'public_page' => [
        'slug' => 'notifications-messaging',
        'hero' => 'Keep guards, clients, and ops informed on every channel.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
