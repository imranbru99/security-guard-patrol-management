<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Models\User;
use App\Modules\VehiclesGate\Filament\Resources\VehicleLogResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Gate Co',
        'slug' => 'gate-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'gate@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers vehicles_gate as a non-core starter module', function (): void {
    $module = Module::query()->where('key', 'vehicles_gate')->firstOrFail();
    expect($module->is_core)->toBeFalse();
    expect($module->plan_tier)->toBe('starter');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'vehicles_gate'))->toBeFalse();
});

it('gates Filament VehicleLogResource when module is off', function (): void {
    expect(VehicleLogResource::canAccess())->toBeFalse();
});

it('gates the vehicles_gate HTTP route when module is off then on', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('vehicles-gate.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'vehicles_gate');

    $this->actingAs($this->user)
        ->get(route('vehicles-gate.show'))
        ->assertOk()
        ->assertJsonPath('module', 'vehicles_gate');
});
