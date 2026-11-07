<?php

declare(strict_types=1);

use App\Modules\LicencesDocuments\Filament\Resources\GuardLicenceResource;
use App\Modules\LicencesDocuments\Filament\Resources\LicenceTypeResource;

return [
    'id' => 'licences',
    'name' => ['en' => 'Licences, Documents & Compliance'],
    'group' => 'People',
    'icon' => 'heroicon-o-document-check',
    'description' => [
        'en' => 'Licence types and guard licence tracking with expiry awareness.',
    ],
    'features' => [
        'Licence type catalogue',
        'Guard licence numbers and expiry dates',
        'Expiry alert note in Filament',
        'Licences API list stub',
    ],
    'depends_on' => ['guards'],
    'conflicts_with' => [],
    'permissions' => [
        'licences.view',
        'licences.manage',
    ],
    'filament_resources' => [
        LicenceTypeResource::class,
        GuardLicenceResource::class,
    ],
    'api_scopes' => ['licences.read'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'small_guarding',
        'corporate_campus',
        'industrial',
        'cash_in_transit',
        'enterprise',
    ],
    'plan_tier' => 'starter',
    'price' => 0,
    'public_page' => [
        'slug' => 'licences-documents',
        'hero' => 'Stay compliant with guard licence and document tracking.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
