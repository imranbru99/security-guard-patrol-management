<?php

declare(strict_types=1);

use App\Modules\UsersSecurity\Filament\Pages\ApiTokensPage;
use App\Modules\UsersSecurity\Filament\Pages\RolesPage;
use App\Modules\UsersSecurity\Filament\Pages\SessionsPage;
use App\Modules\UsersSecurity\Filament\Resources\UserResource;

return [
    'id' => 'users_security',
    'name' => ['en' => 'Users, Roles & Security'],
    'group' => 'Platform Core',
    'icon' => 'heroicon-o-shield-check',
    'description' => [
        'en' => 'Tenant users, roles, login history, sessions, and API token guidance.',
    ],
    'features' => [
        'Tenant-scoped user management',
        'Spatie roles with optional tenant_id',
        'Login history tracking',
        'Session list and API tokens notes',
    ],
    'depends_on' => ['tenancy'],
    'conflicts_with' => [],
    'permissions' => [
        'users_security.view',
        'users_security.manage',
        'users_security.roles.manage',
        'users_security.sessions.view',
        'users_security.tokens.view',
    ],
    'filament_resources' => [
        UserResource::class,
        RolesPage::class,
        SessionsPage::class,
        ApiTokensPage::class,
    ],
    'api_scopes' => ['users_security.users.read'],
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
        'slug' => 'users-security',
        'hero' => 'Manage people, roles, and access securely per tenant.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
