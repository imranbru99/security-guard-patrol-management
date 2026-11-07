<?php

declare(strict_types=1);

use App\Modules\Localisation\Filament\Pages\LocalesPage;

return [
    'id' => 'localisation',
    'name' => ['en' => 'Localisation'],
    'group' => 'Platform Core',
    'icon' => 'heroicon-o-language',
    'description' => [
        'en' => 'Locales, translation overrides, and per-user locale middleware. AI-assisted translation lands later.',
    ],
    'features' => [
        'Supported locale listing from config',
        'Tenant translation overrides',
        'SetUserLocale middleware stub',
        'en/bn language packs for this module',
    ],
    'depends_on' => [],
    'conflicts_with' => [],
    'permissions' => [
        'localisation.view',
        'localisation.manage',
    ],
    'filament_resources' => [
        LocalesPage::class,
    ],
    'api_scopes' => ['localisation.read'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [
        'default_locale' => 'string',
    ],
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
        'slug' => 'localisation',
        'hero' => 'Run GuardOps in the languages your teams speak.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
