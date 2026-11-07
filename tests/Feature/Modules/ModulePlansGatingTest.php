<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Core\Tenancy\TenantContext;
use App\Models\User;
use App\Modules\ModulePlans\Filament\Pages\ModulesPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Plans Co',
        'slug' => 'plans-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'plans@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('is always enabled as a core module without a tenant_modules row', function (): void {
    $module = Module::query()->where('key', 'module_plans')->firstOrFail();
    expect($module->is_core)->toBeTrue();
    expect(app(ModuleManager::class)->enabled($this->tenant, 'module_plans'))->toBeTrue();
});

it('allows Filament ModulesPage access when tenant context is set', function (): void {
    TenantContext::set($this->tenant);
    expect(ModulesPage::canAccess())->toBeTrue();
    TenantContext::clear();
    expect(ModulesPage::canAccess())->toBeFalse();
});

it('serves gated module-plans route when core module is on and 404 when temporarily off', function (): void {
    $this->actingAs($this->user)
        ->get(route('module-plans.show'))
        ->assertOk()
        ->assertSee('Module & Plan Manager module', false);

    Module::query()->where('key', 'module_plans')->update(['is_core' => false]);
    app(ModuleManager::class)->bustCache($this->tenant);

    $this->actingAs($this->user)
        ->get(route('module-plans.show'))
        ->assertNotFound();
});

it('toggles a non-core module via POST route', function (): void {
    $manager = app(ModuleManager::class);
    expect($manager->enabled($this->tenant, 'demo_toggle'))->toBeFalse();

    $this->actingAs($this->user)
        ->post(route('module-plans.toggle'), [
            'module' => 'demo_toggle',
            'enabled' => true,
        ])
        ->assertOk()
        ->assertJsonPath('enabled', true);

    expect($manager->enabled($this->tenant, 'demo_toggle'))->toBeTrue();
});
