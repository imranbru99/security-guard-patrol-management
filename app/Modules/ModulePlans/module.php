<?php

declare(strict_types=1);

use App\Modules\ModulePlans\Filament\Pages\ModulesPage;
use App\Modules\ModulePlans\Filament\Pages\PresetsPage;

return [
    'id' => 'module_plans',
    'name' => ['en' => 'Module & Plan Manager'],
    'group' => 'Platform Core',
    'icon' => 'heroicon-o-squares-2x2',
    'description' => [
        'en' => 'Enable or disable modules per tenant, apply business presets, and manage plan subscriptions.',
    ],
    'features' => [
        'Module enable/disable grid via ModuleManager',
        'Business preset applicator',
        'Plans and subscriptions',
    ],
    'depends_on' => [],
    'conflicts_with' => [],
    'permissions' => [
        'module_plans.view',
        'module_plans.manage',
        'module_plans.presets.apply',
    ],
    'filament_resources' => [
        ModulesPage::class,
        PresetsPage::class,
    ],
    'api_scopes' => ['module_plans.toggle'],
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
        'slug' => 'module-plan-manager',
        'hero' => 'Compose the exact GuardOps stack your company needs.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
