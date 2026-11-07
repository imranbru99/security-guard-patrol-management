<?php

declare(strict_types=1);

use App\Modules\ClientsSites\Filament\Resources\ClientResource;
use App\Modules\ClientsSites\Filament\Resources\PostResource;
use App\Modules\ClientsSites\Filament\Resources\SiteResource;

return [
    'id' => 'clients_sites',
    'name' => ['en' => 'Clients & Sites'],
    'group' => 'Clients',
    'icon' => 'heroicon-o-building-office',
    'description' => [
        'en' => 'Client accounts, contacts, sites with geofence, zones, and guard posts.',
    ],
    'features' => [
        'Client accounts and contacts',
        'Sites with address, map pin, and geofence polygon',
        'Site zones and posts (guard positions)',
        'Post coverage requirements',
    ],
    'depends_on' => [],
    'conflicts_with' => [],
    'permissions' => [
        'clients_sites.view',
        'clients_sites.manage',
        'clients_sites.clients.manage',
        'clients_sites.sites.manage',
        'clients_sites.posts.manage',
    ],
    'filament_resources' => [
        ClientResource::class,
        SiteResource::class,
        PostResource::class,
    ],
    'api_scopes' => ['clients_sites.read', 'clients_sites.write'],
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
    'plan_tier' => 'starter',
    'price' => 0,
    'public_page' => [
        'slug' => 'clients-sites',
        'hero' => 'Manage clients, sites, zones, and posts in one place.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
