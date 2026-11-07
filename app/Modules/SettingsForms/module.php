<?php

declare(strict_types=1);

use App\Modules\SettingsForms\Filament\Pages\FormBuilderPage;
use App\Modules\SettingsForms\Filament\Resources\CustomFieldResource;
use App\Modules\SettingsForms\Filament\Resources\FormTemplateResource;

return [
    'id' => 'settings_forms',
    'name' => ['en' => 'Settings, Custom Fields & Forms'],
    'group' => 'Platform Core',
    'icon' => 'heroicon-o-adjustments-horizontal',
    'description' => [
        'en' => 'Custom fields, form templates, and submissions reused across inspections, incidents, and HR.',
    ],
    'features' => [
        'Tenant-scoped custom fields by entity type',
        'Reusable form templates',
        'Form submission API',
        'Simple form builder page',
    ],
    'depends_on' => [],
    'conflicts_with' => [],
    'permissions' => [
        'settings_forms.view',
        'settings_forms.manage',
        'settings_forms.submit',
    ],
    'filament_resources' => [
        CustomFieldResource::class,
        FormTemplateResource::class,
        FormBuilderPage::class,
    ],
    'api_scopes' => ['settings_forms.read', 'settings_forms.write'],
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
        'slug' => 'settings-forms',
        'hero' => 'Configure custom fields and forms for every workflow.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
