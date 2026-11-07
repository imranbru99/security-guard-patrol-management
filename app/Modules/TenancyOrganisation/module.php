<?php

declare(strict_types=1);

use App\Modules\TenancyOrganisation\Filament\Pages\OrganisationSettingsPage;
use App\Modules\TenancyOrganisation\Filament\Resources\BranchResource;
use App\Modules\TenancyOrganisation\Filament\Resources\DepartmentResource;

return [
    'id' => 'tenancy',
    'name' => ['en' => 'Tenancy & Organisation'],
    'group' => 'Platform Core',
    'icon' => 'heroicon-o-building-office-2',
    'description' => [
        'en' => 'Branches, departments, and organisation branding for the current tenant.',
    ],
    'features' => [
        'Branch hierarchy with region',
        'Departments scoped to tenant',
        'Organisation branding settings',
        'Branches API stubs',
    ],
    'depends_on' => [],
    'conflicts_with' => [],
    'permissions' => [
        'tenancy.view',
        'tenancy.manage',
        'tenancy.branches.manage',
        'tenancy.departments.manage',
    ],
    'filament_resources' => [
        BranchResource::class,
        DepartmentResource::class,
        OrganisationSettingsPage::class,
    ],
    'api_scopes' => ['tenancy.branches.read', 'tenancy.branches.write'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [
        'branding.logo_url' => 'string',
        'branding.primary_color' => 'string',
        'branding.accent_color' => 'string',
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
        'slug' => 'tenancy-organisation',
        'hero' => 'Structure your security company with branches and departments.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
