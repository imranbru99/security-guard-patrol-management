<?php

declare(strict_types=1);

use App\Modules\Privacy\Filament\Pages\PrivacyCentrePage;

return [
    'id' => 'privacy',
    'name' => ['en' => 'Data Privacy & Retention Centre'],
    'group' => 'Commerce',
    'icon' => 'heroicon-o-shield-check',
    'description' => [
        'en' => 'Consent records, retention policies, DSAR requests, and erasure job stubs.',
    ],
    'features' => [
        'Consent, RetentionPolicy, DsarRequest models',
        'Filament PrivacyCentrePage',
        'Erasure job stub',
    ],
    'depends_on' => [],
    'conflicts_with' => [],
    'permissions' => [
        'privacy.view',
        'privacy.manage',
    ],
    'filament_resources' => [
        PrivacyCentrePage::class,
    ],
    'api_scopes' => ['privacy.read'],
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
        'slug' => 'data-privacy-retention',
        'hero' => 'Manage consent, retention, and DSAR workflows.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
