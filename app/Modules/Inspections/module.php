<?php

declare(strict_types=1);

use App\Modules\Inspections\Filament\Resources\InspectionResource;

return [
    'id' => 'inspections',
    'name' => ['en' => 'Inspections, Audits & Checklists'],
    'group' => 'Access',
    'icon' => 'heroicon-o-clipboard-document-check',
    'description' => [
        'en' => 'Template-driven site, safety, and equipment inspections with scoring.',
    ],
    'features' => [
        'Inspection templates',
        'Scored inspection results',
        'Photo evidence placeholders',
        'Client-visible option',
    ],
    'depends_on' => ['clients_sites', 'settings_forms'],
    'conflicts_with' => [],
    'permissions' => [
        'inspections.view',
        'inspections.manage',
    ],
    'filament_resources' => [
        InspectionResource::class,
    ],
    'api_scopes' => ['inspections.read', 'inspections.write'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'corporate_campus',
        'enterprise',
    ],
    'plan_tier' => 'pro',
    'price' => 0,
    'public_page' => [
        'slug' => 'inspections-audits',
        'hero' => 'Run scored checklists across every site.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
