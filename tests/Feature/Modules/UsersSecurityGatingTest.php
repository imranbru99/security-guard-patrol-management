<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Core\Tenancy\TenantContext;
use App\Models\User;
use App\Modules\UsersSecurity\Filament\Pages\SessionsPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Users Co',
        'slug' => 'users-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'users@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('is always enabled as a core module without a tenant_modules row', function (): void {
    $module = Module::query()->where('key', 'users_security')->firstOrFail();
    expect($module->is_core)->toBeTrue();
    expect(app(ModuleManager::class)->enabled($this->tenant, 'users_security'))->toBeTrue();
});

it('allows Filament page access when tenant context is set', function (): void {
    TenantContext::set($this->tenant);
    expect(SessionsPage::canAccess())->toBeTrue();
    TenantContext::clear();
    expect(SessionsPage::canAccess())->toBeFalse();
});

it('serves gated users-security route when core module is on and 404 when temporarily off', function (): void {
    $this->actingAs($this->user)
        ->get(route('users-security.show'))
        ->assertOk()
        ->assertSee('Users, Roles & Security module', false);

    Module::query()->where('key', 'users_security')->update(['is_core' => false]);
    app(ModuleManager::class)->bustCache($this->tenant);

    $this->actingAs($this->user)
        ->get(route('users-security.show'))
        ->assertNotFound();
});
