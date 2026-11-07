<?php

declare(strict_types=1);

use App\Modules\ContractsSlas\Filament\Resources\ContractResource;

return [
    'id' => 'contracts_slas',
    'name' => ['en' => 'Contracts & SLAs'],
    'group' => 'Clients',
    'icon' => 'heroicon-o-document-text',
    'description' => [
        'en' => 'Client contracts, billable rates, SLA targets, and scoring.',
    ],
    'features' => [
        'Contract terms linked to clients and sites',
        'Contract rates in cents with currency',
        'SLA targets and score stubs',
        'SlaScorer service stub',
    ],
    'depends_on' => ['clients_sites'],
    'conflicts_with' => [],
    'permissions' => [
        'contracts_slas.view',
        'contracts_slas.manage',
    ],
    'filament_resources' => [
        ContractResource::class,
    ],
    'api_scopes' => ['contracts_slas.read'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'small_guarding',
        'corporate_campus',
        'industrial',
        'enterprise',
    ],
    'plan_tier' => 'starter',
    'price' => 0,
    'public_page' => [
        'slug' => 'contracts-slas',
        'hero' => 'Track contracts, rates, and SLA performance.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
