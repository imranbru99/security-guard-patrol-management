<?php

declare(strict_types=1);

use App\Modules\ShiftScheduling\Filament\Pages\RosterConflictPage;
use App\Modules\ShiftScheduling\Filament\Resources\ShiftAssignmentResource;
use App\Modules\ShiftScheduling\Filament\Resources\ShiftResource;

return [
    'id' => 'shift_scheduling',
    'name' => ['en' => 'Shift Scheduling'],
    'group' => 'Scheduling',
    'icon' => 'heroicon-o-calendar-days',
    'description' => [
        'en' => 'Shift templates, rosters, assignments, coverage requirements, conflict detection, and auto-scheduling.',
    ],
    'features' => [
        'Shift templates and rotations',
        'Rosters and shifts',
        'Assignments, claims, swaps, and bids',
        'Coverage requirements',
        'RosterConflictEngine (overlap + rest period)',
        'AutoScheduler greedy fill',
        'Roster conflict dry-run page',
    ],
    'depends_on' => ['guards', 'clients_sites'],
    'conflicts_with' => [],
    'permissions' => [
        'shift_scheduling.view',
        'shift_scheduling.manage',
        'shift_scheduling.shifts.manage',
        'shift_scheduling.assignments.manage',
    ],
    'filament_resources' => [
        ShiftResource::class,
        ShiftAssignmentResource::class,
        RosterConflictPage::class,
    ],
    'api_scopes' => ['shift_scheduling.read', 'shift_scheduling.write'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [
        [
            'key' => 'min_rest_hours',
            'type' => 'number',
            'default' => 8,
            'label' => 'Minimum rest hours between shifts',
        ],
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
    'plan_tier' => 'starter',
    'price' => 0,
    'public_page' => [
        'slug' => 'shift-scheduling',
        'hero' => 'Build conflict-free rosters with deterministic scheduling engines.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
