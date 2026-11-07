<?php

declare(strict_types=1);

use App\Modules\AuditCompliance\Filament\Resources\AuditEventResource;

return [
    'id' => 'audit',
    'name' => ['en' => 'Audit, Activity & Compliance'],
    'group' => 'Platform Core',
    'icon' => 'heroicon-o-clipboard-document-list',
    'description' => [
        'en' => 'Append-only audit trail with SHA-256 hash chaining for compliance.',
    ],
    'features' => [
        'Audit event log with before/after snapshots',
        'SHA-256 hash chain verification',
        'Read-only Filament resource',
    ],
    'depends_on' => ['tenancy'],
    'conflicts_with' => [],
    'permissions' => [
        'audit.view',
        'audit.export',
    ],
    'filament_resources' => [
        AuditEventResource::class,
    ],
    'api_scopes' => ['audit.read'],
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
        'slug' => 'audit-compliance',
        'hero' => 'Prove who changed what, with a verifiable hash chain.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
