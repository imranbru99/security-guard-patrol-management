<?php

declare(strict_types=1);

use App\Modules\Expenses\Filament\Resources\ExpenseResource;

return [
    'id' => 'expenses',
    'name' => ['en' => 'Expenses & Procurement'],
    'group' => 'Commerce',
    'icon' => 'heroicon-o-shopping-bag',
    'description' => [
        'en' => 'Expense claims, purchase orders, and vendors for procurement.',
    ],
    'features' => [
        'Expense claims',
        'Purchase orders',
        'Vendors',
        'Filament ExpenseResource',
    ],
    'depends_on' => [],
    'conflicts_with' => [],
    'permissions' => [
        'expenses.view',
        'expenses.manage',
    ],
    'filament_resources' => [
        ExpenseResource::class,
    ],
    'api_scopes' => ['expenses.read'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'enterprise',
    ],
    'plan_tier' => 'pro',
    'price' => 0,
    'public_page' => [
        'slug' => 'expenses-procurement',
        'hero' => 'Track expenses, POs, and vendors.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
