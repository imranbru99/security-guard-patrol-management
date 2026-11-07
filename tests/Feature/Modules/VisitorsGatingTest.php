<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Models\User;
use App\Modules\Visitors\Filament\Resources\VisitResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Visitors Co',
        'slug' => 'visitors-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'visitors@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers visitors as a non-core starter module depending on clients_sites', function (): void {
    $module = Module::query()->where('key', 'visitors')->firstOrFail();
    expect($module->is_core)->toBeFalse();
    expect($module->plan_tier)->toBe('starter');
    expect($module->dependencyKeys())->toContain('clients_sites');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'visitors'))->toBeFalse();
});

it('gates Filament VisitResource when module is off', function (): void {
    expect(VisitResource::canAccess())->toBeFalse();
});

it('gates the visitors HTTP route when module is off then on', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('visitors.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'visitors');

    $this->actingAs($this->user)
        ->get(route('visitors.show'))
        ->assertOk()
        ->assertJsonPath('module', 'visitors');
});

it('gates the public kiosk pre-register route when module is off then on', function (): void {
    $manager = app(ModuleManager::class);

    $this->get(route('visitors.kiosk.show', ['tenantSlug' => $this->tenant->slug]))
        ->assertNotFound();

    $manager->enable($this->tenant, 'visitors');

    $this->get(route('visitors.kiosk.show', ['tenantSlug' => $this->tenant->slug]))
        ->assertOk()
        ->assertJsonPath('mode', 'kiosk');
});
