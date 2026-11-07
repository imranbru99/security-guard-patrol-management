<?php

declare(strict_types=1);

use App\Modules\LeadsCrm\Filament\Resources\LeadResource;
use App\Modules\LeadsCrm\Filament\Resources\QuoteResource;

return [
    'id' => 'leads_crm',
    'name' => ['en' => 'Leads, Quotes & CRM'],
    'group' => 'Clients',
    'icon' => 'heroicon-o-funnel',
    'description' => [
        'en' => 'Public quote intake, lead pipeline, and quote builder stubs.',
    ],
    'features' => [
        'Lead capture from public quote request',
        'Quote and quote line items (amount_cents)',
        'Filament Lead and Quote resources',
        'PDF quotation stub later',
    ],
    'depends_on' => [],
    'conflicts_with' => [],
    'permissions' => [
        'leads_crm.view',
        'leads_crm.manage',
        'leads_crm.quotes.manage',
    ],
    'filament_resources' => [
        LeadResource::class,
        QuoteResource::class,
    ],
    'api_scopes' => ['leads_crm.read', 'leads_crm.write'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'small_guarding',
        'event_security',
        'enterprise',
    ],
    'plan_tier' => 'starter',
    'price' => 0,
    'public_page' => [
        'slug' => 'leads-crm',
        'hero' => 'Turn public quote requests into contracts.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
