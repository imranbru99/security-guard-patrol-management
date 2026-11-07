<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Core\Tenancy\TenantContext;
use App\Models\User;
use App\Modules\ClientPortalShell\Filament\Pages\ClientPortalSettingsPage;
use App\Modules\ClientPortalShell\Filament\Pages\ClientSitesStatusPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Portal Co',
        'slug' => 'portal-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'portal@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers client_portal as a non-core starter module depending on clients_sites', function (): void {
    $module = Module::query()->where('key', 'client_portal')->firstOrFail();
    expect($module->is_core)->toBeFalse();
    expect($module->plan_tier)->toBe('starter');
    expect($module->depends_on)->toContain('clients_sites');
});

it('gates Filament portal pages when module is off then on', function (): void {
    expect(ClientPortalSettingsPage::canAccess())->toBeFalse();
    expect(ClientSitesStatusPage::canAccess())->toBeFalse();

    app(ModuleManager::class)->enable($this->tenant, 'client_portal');
    TenantContext::set($this->tenant);

    expect(ClientPortalSettingsPage::canAccess())->toBeTrue();
    expect(ClientSitesStatusPage::canAccess())->toBeTrue();

    TenantContext::clear();
});

it('gates client portal HTTP routes when module is off then on', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('client-portal.show'))
        ->assertNotFound();

    $this->actingAs($this->user)
        ->get(route('client-portal.status'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'client_portal');

    $this->actingAs($this->user)
        ->get(route('client-portal.show'))
        ->assertOk()
        ->assertJsonPath('module', 'client_portal');

    $this->actingAs($this->user)
        ->get(route('client-portal.status'))
        ->assertOk()
        ->assertJsonPath('module', 'client_portal');
});
