<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Core\Tenancy\TenantContext;
use App\Models\User;
use App\Modules\TenancyOrganisation\Filament\Pages\OrganisationSettingsPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Tenancy Co',
        'slug' => 'tenancy-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'tenancy@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('is always enabled as a core module without a tenant_modules row', function (): void {
    $module = Module::query()->where('key', 'tenancy')->firstOrFail();
    expect($module->is_core)->toBeTrue();
    expect($module->plan_tier)->toBe('core');

    expect(app(ModuleManager::class)->enabled($this->tenant, 'tenancy'))->toBeTrue();
});

it('allows Filament page access when tenant context is set', function (): void {
    TenantContext::set($this->tenant);
    expect(OrganisationSettingsPage::canAccess())->toBeTrue();
    TenantContext::clear();
    expect(OrganisationSettingsPage::canAccess())->toBeFalse();
});

it('serves gated tenancy route when core module is on and 404 when temporarily off', function (): void {
    $this->actingAs($this->user)
        ->get(route('tenancy.show'))
        ->assertOk()
        ->assertSee('Tenancy & Organisation module', false);

    $module = Module::query()->where('key', 'tenancy')->firstOrFail();
    $module->update(['is_core' => false]);
    app(ModuleManager::class)->bustCache($this->tenant);

    $this->actingAs($this->user)
        ->get(route('tenancy.show'))
        ->assertNotFound();
});
