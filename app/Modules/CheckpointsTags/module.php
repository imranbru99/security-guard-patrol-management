<?php

declare(strict_types=1);

use App\Modules\CheckpointsTags\Filament\Pages\CheckpointTagSheetsPage;
use App\Modules\CheckpointsTags\Filament\Resources\CheckpointResource;

return [
    'id' => 'checkpoints',
    'name' => ['en' => 'Checkpoints & Tags'],
    'group' => 'Patrols',
    'icon' => 'heroicon-o-qr-code',
    'description' => [
        'en' => 'Site checkpoints with QR, NFC, GPS, and BLE verification plus printable tag sheets.',
    ],
    'features' => [
        'Checkpoints per site and zone',
        'Verification types: QR, NFC, GPS, BLE',
        'Tag registry with damaged/missing status',
        'Photo/notes requirements and hazard prompts',
        'Printable PDF tag sheet stub',
    ],
    'depends_on' => ['clients_sites'],
    'conflicts_with' => [],
    'permissions' => [
        'checkpoints.view',
        'checkpoints.manage',
    ],
    'filament_resources' => [
        CheckpointResource::class,
        CheckpointTagSheetsPage::class,
    ],
    'api_scopes' => ['checkpoints.read'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'small_guarding',
        'residential_society',
        'mall_retail',
        'enterprise',
    ],
    'plan_tier' => 'starter',
    'price' => 0,
    'public_page' => [
        'slug' => 'checkpoints-tags',
        'hero' => 'Prove patrols with QR, NFC, GPS, and BLE checkpoints.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
