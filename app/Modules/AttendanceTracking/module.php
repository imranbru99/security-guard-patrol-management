<?php

declare(strict_types=1);

use App\Modules\AttendanceTracking\Filament\Pages\DeviceManagerPage;
use App\Modules\AttendanceTracking\Filament\Resources\AttendanceSummaryResource;

return [
    'id' => 'attendance',
    'name' => ['en' => 'Attendance & Time Tracking'],
    'group' => 'Scheduling',
    'icon' => 'heroicon-o-clock',
    'description' => [
        'en' => 'Daily attendance summaries, exceptions, timesheets, and virtual GPS check-in on top of attendance-hub.',
    ],
    'features' => [
        'Attendance summaries from AttendanceCalculator',
        'Exceptions queue (missed punch, out-of-geofence)',
        'Timesheets',
        'Virtual check-in API with Geo haversine / polygon',
        'Device manager page linking hub concepts',
        'Scheduled attendance:sync',
    ],
    'depends_on' => ['shift_scheduling'],
    'conflicts_with' => [],
    'permissions' => [
        'attendance.view',
        'attendance.manage',
        'attendance.summaries.manage',
        'attendance.devices.view',
    ],
    'filament_resources' => [
        AttendanceSummaryResource::class,
        DeviceManagerPage::class,
    ],
    'api_scopes' => ['attendance.read', 'attendance.write'],
    'scheduled_jobs' => [
        [
            'command' => 'attendance:sync',
            'cron' => '*/15 * * * *',
            'description' => 'Pull/sync attendance punches from registered devices via attendance-hub',
        ],
    ],
    'widgets' => [],
    'settings_schema' => [
        [
            'key' => 'default_geofence_radius_meters',
            'type' => 'number',
            'default' => 100,
            'label' => 'Default virtual check-in geofence radius (meters)',
        ],
    ],
    'default_on_presets' => [
        'small_guarding',
        'residential_society',
        'corporate_campus',
        'mall_retail',
        'industrial',
        'enterprise',
    ],
    'plan_tier' => 'starter',
    'price' => 0,
    'public_page' => [
        'slug' => 'attendance-time-tracking',
        'hero' => 'Clock-ins from terminals, ADMS, and virtual GPS — summarised for payroll.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
