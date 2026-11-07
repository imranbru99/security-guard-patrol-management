<?php

declare(strict_types=1);

use App\Modules\PayrollExport\Filament\Resources\PayrollRunResource;

return [
    'id' => 'payroll_export',
    'name' => ['en' => 'Payroll Export & Allowances'],
    'group' => 'People',
    'icon' => 'heroicon-o-banknotes',
    'description' => [
        'en' => 'Export-ready payroll runs, lines, and allowances — not a statutory payroll engine. CSV export stub for payroll systems.',
    ],
    'features' => [
        'Payroll runs',
        'Payroll lines per guard',
        'Allowances catalogue',
        'CSV export action stub',
        'Payroll API list stub',
    ],
    'depends_on' => ['guards'],
    'conflicts_with' => [],
    'permissions' => [
        'payroll_export.view',
        'payroll_export.manage',
        'payroll_export.export',
    ],
    'filament_resources' => [
        PayrollRunResource::class,
    ],
    'api_scopes' => ['payroll_export.read'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'enterprise',
    ],
    'plan_tier' => 'pro',
    'price' => 0,
    'public_page' => [
        'slug' => 'payroll-export',
        'hero' => 'Export payroll-ready data without running statutory payroll.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
