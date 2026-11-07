<?php

declare(strict_types=1);

use App\Modules\MediaVault\Filament\Resources\MediaAssetResource;

return [
    'id' => 'media_vault',
    'name' => ['en' => 'Files, Media & Evidence Vault'],
    'group' => 'Platform Core',
    'icon' => 'heroicon-o-archive-box',
    'description' => [
        'en' => 'Hashed uploads, evidence custody chain, and signed downloads for incident media.',
    ],
    'features' => [
        'SHA-256 hashed media assets',
        'Evidence custody event chain',
        'Signed download routes',
        'Filament media catalogue',
    ],
    'depends_on' => [],
    'conflicts_with' => [],
    'permissions' => [
        'media_vault.view',
        'media_vault.manage',
        'media_vault.download',
    ],
    'filament_resources' => [
        MediaAssetResource::class,
    ],
    'api_scopes' => ['media_vault.read', 'media_vault.write'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [
        'default_disk' => 'string',
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
        'slug' => 'media-vault',
        'hero' => 'Tamper-evident evidence storage for every incident.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
