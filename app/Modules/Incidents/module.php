<?php

declare(strict_types=1);

use App\Modules\Incidents\Filament\Resources\IncidentResource;

return [
    'id' => 'incidents',
    'name' => ['en' => 'Incident Management'],
    'group' => 'Safety',
    'icon' => 'heroicon-o-exclamation-triangle',
    'description' => [
        'en' => 'Configurable incident types, evidence vault links, status workflow, and corrective actions.',
    ],
    'features' => [
        'Types, severity, people, vehicles, witnesses',
        'Immutable evidence notes linked to MediaVault',
        'Status workflow with timeline',
        'Linked incidents and corrective actions',
    ],
    'depends_on' => ['clients_sites', 'media_vault'],
    'conflicts_with' => [],
    'permissions' => [
        'incidents.view',
        'incidents.manage',
        'incidents.transition',
    ],
    'filament_resources' => [
        IncidentResource::class,
    ],
    'api_scopes' => ['incidents.read', 'incidents.write'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'small_guarding',
        'mall_retail',
        'event_security',
        'enterprise',
    ],
    'plan_tier' => 'starter',
    'price' => 0,
    'public_page' => [
        'slug' => 'incidents',
        'hero' => 'Capture, triage, and close incidents with a tamper-evident evidence chain.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
