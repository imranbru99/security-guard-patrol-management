<?php

declare(strict_types=1);

use App\Modules\Billing\Filament\Resources\InvoiceResource;

return [
    'id' => 'billing',
    'name' => ['en' => 'Billing & Invoicing'],
    'group' => 'Commerce',
    'icon' => 'heroicon-o-currency-dollar',
    'description' => [
        'en' => 'Client invoices from contract rates, payments, credit notes, and PDF stubs.',
    ],
    'features' => [
        'Invoices, lines, payments, credit notes (amount_cents)',
        'InvoiceGenerator from contract rates stub',
        'FakePaymentDriver',
        'UnicodePdf invoice PDF stub',
        'Filament InvoiceResource',
    ],
    'depends_on' => ['contracts_slas'],
    'conflicts_with' => [],
    'permissions' => [
        'billing.view',
        'billing.manage',
    ],
    'filament_resources' => [
        InvoiceResource::class,
    ],
    'api_scopes' => ['billing.read'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'enterprise',
        'corporate_campus',
    ],
    'plan_tier' => 'pro',
    'price' => 0,
    'public_page' => [
        'slug' => 'billing-invoicing',
        'hero' => 'Generate invoices from contract rates with PDF delivery.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
