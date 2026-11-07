<?php

declare(strict_types=1);

use App\Modules\VehiclesGate\Filament\Resources\VehicleLogResource;

return [
    'id' => 'vehicles_gate',
    'name' => ['en' => 'Vehicle, Gate & Parking Log'],
    'group' => 'Access',
    'icon' => 'heroicon-o-truck',
    'description' => [
        'en' => 'Vehicle in/out logs, material gate passes, and parking slot allocation.',
    ],
    'features' => [
        'Vehicle entry/exit log with plate and driver',
        'Gate/material passes with approvals',
        'Parking slot allocation and violations',
    ],
    'depends_on' => ['clients_sites'],
    'conflicts_with' => [],
    'permissions' => [
        'vehicles_gate.view',
        'vehicles_gate.manage',
    ],
    'filament_resources' => [
        VehicleLogResource::class,
    ],
    'api_scopes' => ['vehicles_gate.read', 'vehicles_gate.write'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'residential_society',
        'industrial',
        'enterprise',
    ],
    'plan_tier' => 'starter',
    'price' => 0,
    'public_page' => [
        'slug' => 'vehicle-gate-parking',
        'hero' => 'Log vehicles at the gate and manage parking.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
