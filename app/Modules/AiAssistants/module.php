<?php

declare(strict_types=1);

use App\Modules\AiAssistants\Filament\Pages\AiAssistantsPage;

return [
    'id' => 'ai_assistants',
    'name' => ['en' => 'AI Assistants'],
    'group' => 'Commerce',
    'icon' => 'heroicon-o-sparkles',
    'description' => [
        'en' => 'Draft-only AI copilots for incidents and ops with per-tenant job budgets via AIHub.',
    ],
    'features' => [
        'AiDraft storage for reviewed-only publish flow',
        'IncidentCopilotService with AIHub or canned schema',
        'Filament AiAssistantsPage',
        'Budget tag forJob tenant:{id}:ai_assistants',
    ],
    'depends_on' => [],
    'conflicts_with' => [],
    'permissions' => [
        'ai_assistants.view',
        'ai_assistants.manage',
    ],
    'filament_resources' => [
        AiAssistantsPage::class,
    ],
    'api_scopes' => ['ai_assistants.read'],
    'scheduled_jobs' => [],
    'widgets' => [],
    'settings_schema' => [],
    'default_on_presets' => [
        'enterprise',
    ],
    'plan_tier' => 'addon',
    'price' => 0,
    'public_page' => [
        'slug' => 'ai-assistants',
        'hero' => 'Draft-only AI copilots with tenant budgets.',
        'screenshot' => null,
    ],
    'demo_seeder' => null,
];
