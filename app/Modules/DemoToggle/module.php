<?php

declare(strict_types=1);
use App\Modules\DemoToggle\Filament\Pages\DemoTogglePage;

return [
    'id' => 'demo_toggle',
    'name' => ['en' => 'Demo Toggle'],
    'group' => 'Platform Core',
    'icon' => 'heroicon-o-puzzle-piece',
    'description' => [
        'en' => 'Phase 0 proof module used to verify ON/OFF gating across routes, menus, API and Blade.',
    ],
    'features' => [
        'Toggleable demo page in company panel',
        'Gated HTTP route under /app/demo-toggle',
        'API scope demo_toggle.read',
    ],
    'depends_on' => [],
    'conflicts_with' => [],
    'permissions' => [
        'demo_toggle.view',
        'demo_toggle.manage',
    ],
    'filament_resources' => [
        DemoTogglePage::class,
    ],
    'api_scopes' => ['demo_toggle.read'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => ['enterprise'],
    'plan_tier' => 'addon',
    'price' => 0,
    'public_page' => [
        'slug' => 'demo-toggle',
        'hero' => 'Prove module gating works end-to-end.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
