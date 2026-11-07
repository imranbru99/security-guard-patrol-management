<?php

declare(strict_types=1);

use App\Modules\Catalog\Filament\Resources\CatalogItemResource;

return [
    'id' => 'catalog',
    'name' => ['en' => 'Services & Equipment Catalog'],
    'group' => 'Commerce',
    'icon' => 'heroicon-o-rectangle-stack',
    'description' => [
        'en' => 'Service packages and equipment listings mapped to ai-catalog concepts with AI copy assist.',
    ],
    'features' => [
        'Catalog categories and items',
        'Filament CatalogItemResource',
        'Public catalog list stub',
        'CatalogCopyService demo with AIHub::fake',
    ],
    'depends_on' => [],
    'conflicts_with' => [],
    'permissions' => [
        'catalog.view',
        'catalog.manage',
    ],
    'filament_resources' => [
        CatalogItemResource::class,
    ],
    'api_scopes' => ['catalog.read'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'enterprise',
        'small_guarding',
    ],
    'plan_tier' => 'pro',
    'price' => 0,
    'public_page' => [
        'slug' => 'services-equipment-catalog',
        'hero' => 'Publish service packages and equipment with AI-assisted listings.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
