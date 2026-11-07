<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Core\Tenancy\TenantContext;
use App\Models\User;
use App\Modules\AiAssistants\Filament\Pages\AiAssistantsPage;
use App\Modules\AiAssistants\Services\IncidentCopilotService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'AI Co',
        'slug' => 'ai-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'ai@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers ai_assistants as a non-core addon module', function (): void {
    expect(Module::query()->where('key', 'ai_assistants')->where('is_core', false)->exists())->toBeTrue();
    expect(Module::query()->where('key', 'ai_assistants')->value('plan_tier'))->toBe('addon');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'ai_assistants'))->toBeFalse();
});

it('gates the ai_assistants HTTP route when module is off', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('ai-assistants.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'ai_assistants');

    $this->actingAs($this->user)
        ->get(route('ai-assistants.show'))
        ->assertOk()
        ->assertJsonPath('module', 'ai_assistants');
});

it('allows AiAssistantsPage only with tenant context', function (): void {
    TenantContext::set($this->tenant);
    app(ModuleManager::class)->enable($this->tenant, 'ai_assistants');
    expect(AiAssistantsPage::canAccess())->toBeTrue();
    TenantContext::clear();
    expect(AiAssistantsPage::canAccess())->toBeFalse();
});

it('creates a draft via IncidentCopilotService with tenant job tag', function (): void {
    TenantContext::set($this->tenant);
    $out = app(IncidentCopilotService::class)->draft($this->tenant, ['title' => 'Gate breach'], $this->user->id);
    TenantContext::clear();

    expect($out['draft']->job_tag)->toBe('tenant:'.$this->tenant->id.':ai_assistants');
    expect($out['draft']->status)->toBe('draft');
    expect($out['result'])->toHaveKey('summary');
});
