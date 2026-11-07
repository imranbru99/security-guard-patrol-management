<?php

declare(strict_types=1);

use App\Modules\Microsite\Filament\Resources\TenantPageResource;

return [
    'id' => 'microsite',
    'name' => ['en' => 'Tenant Microsite & Public Site Builder'],
    'group' => 'Commerce',
    'icon' => 'heroicon-o-globe-alt',
    'description' => [
        'en' => 'Optional public microsite pages per tenant, routed by tenant slug.',
    ],
    'features' => [
        'TenantPage model',
        'Public microsite route by tenant slug',
        'Filament TenantPageResource',
    ],
    'depends_on' => [],
    'conflicts_with' => [],
    'permissions' => [
        'microsite.view',
        'microsite.manage',
    ],
    'filament_resources' => [
        TenantPageResource::class,
    ],
    'api_scopes' => ['microsite.read'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [
        'microsite.theme' => 'string',
    ],
    'default_on_presets' => [
        'small_guarding',
        'enterprise',
    ],
    'plan_tier' => 'starter',
    'price' => 0,
    'public_page' => [
        'slug' => 'tenant-microsite',
        'hero' => 'Publish a branded public microsite for each tenant.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
