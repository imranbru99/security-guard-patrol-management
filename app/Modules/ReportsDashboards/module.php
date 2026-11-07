<?php

declare(strict_types=1);

use App\Modules\ReportsDashboards\Filament\Pages\ReportBuilderPage;
use App\Modules\ReportsDashboards\Filament\Widgets\KpiPlaceholderWidget;

return [
    'id' => 'reports',
    'name' => ['en' => 'Reports, Exports & Dashboards'],
    'group' => 'Platform Core',
    'icon' => 'heroicon-o-chart-bar',
    'description' => [
        'en' => 'Saved reports, CSV export stubs, and KPI dashboard placeholders.',
    ],
    'features' => [
        'Saved report definitions with filters and schedule',
        'Report builder page shell',
        'CSV export stub',
        'KPI placeholder widget',
    ],
    'depends_on' => [],
    'conflicts_with' => [],
    'permissions' => [
        'reports.view',
        'reports.manage',
        'reports.export',
    ],
    'filament_resources' => [
        ReportBuilderPage::class,
    ],
    'api_scopes' => ['reports.read', 'reports.export'],
    'scheduled_jobs' => [],
    'widgets' => [
        KpiPlaceholderWidget::class,
    ],
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
        'slug' => 'reports-dashboards',
        'hero' => 'Operational reports and exports for managers and clients.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
