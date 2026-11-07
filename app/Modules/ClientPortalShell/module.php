<?php

declare(strict_types=1);

use App\Modules\ClientPortalShell\Filament\Pages\ClientPortalSettingsPage;
use App\Modules\ClientPortalShell\Filament\Pages\ClientSitesStatusPage;

return [
    'id' => 'client_portal',
    'name' => ['en' => 'Client Portal'],
    'group' => 'Clients',
    'icon' => 'heroicon-o-computer-desktop',
    'description' => [
        'en' => 'Client-facing portal shell for site status and branded settings.',
    ],
    'features' => [
        'Client panel sites status page',
        'Tenant app portal settings page',
        'Module-gated portal routes',
    ],
    'depends_on' => ['clients_sites'],
    'conflicts_with' => [],
    'permissions' => [
        'client_portal.view',
        'client_portal.manage',
    ],
    'filament_resources' => [
        ClientPortalSettingsPage::class,
        ClientSitesStatusPage::class,
    ],
    'api_scopes' => ['client_portal.read'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [
        'portal.welcome_message' => 'string',
        'portal.show_guard_names' => 'boolean',
    ],
    'default_on_presets' => [
        'corporate_campus',
        'mall_retail',
        'residential_society',
        'enterprise',
    ],
    'plan_tier' => 'starter',
    'price' => 0,
    'public_page' => [
        'slug' => 'client-portal',
        'hero' => 'Give clients a live view of their sites.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
