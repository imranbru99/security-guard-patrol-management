<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Core\Tenancy\TenantContext;
use App\Models\User;
use App\Modules\Localisation\Filament\Pages\LocalesPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Locale Co',
        'slug' => 'locale-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'locale@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('keeps localisation enabled as a core module after sync', function (): void {
    $manager = app(ModuleManager::class);

    expect(Module::query()->where('key', 'localisation')->where('is_core', true)->exists())->toBeTrue();
    expect($manager->enabled($this->tenant, 'localisation'))->toBeTrue();
});

it('serves the gated localisation route when tenant is set', function (): void {
    $this->actingAs($this->user)
        ->get(route('localisation.show'))
        ->assertOk()
        ->assertJsonPath('module', 'localisation');
});

it('allows LocalesPage access when tenant context is set', function (): void {
    TenantContext::set($this->tenant);
    expect(LocalesPage::canAccess())->toBeTrue();
    TenantContext::clear();
    expect(LocalesPage::canAccess())->toBeFalse();
});
