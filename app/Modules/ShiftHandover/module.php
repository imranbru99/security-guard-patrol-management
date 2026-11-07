<?php

declare(strict_types=1);

use App\Modules\ShiftHandover\Filament\Resources\HandoverResource;

return [
    'id' => 'handover',
    'name' => ['en' => 'Shift Handover & Post Log'],
    'group' => 'Scheduling',
    'icon' => 'heroicon-o-arrows-right-left',
    'description' => [
        'en' => 'Structured shift handovers, open items, and post-order acknowledgements.',
    ],
    'features' => [
        'Handover notes between outgoing and incoming guards',
        'Open items carried forward',
        'Post orders with read-receipts (acks)',
    ],
    'depends_on' => ['shift_scheduling'],
    'conflicts_with' => [],
    'permissions' => [
        'handover.view',
        'handover.manage',
    ],
    'filament_resources' => [
        HandoverResource::class,
    ],
    'api_scopes' => ['handover.read', 'handover.write'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'small_guarding',
        'corporate_campus',
        'mall_retail',
        'industrial',
        'enterprise',
    ],
    'plan_tier' => 'starter',
    'price' => 0,
    'public_page' => [
        'slug' => 'shift-handover',
        'hero' => 'Pass the post cleanly — notes, open items, and post-order acks.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
