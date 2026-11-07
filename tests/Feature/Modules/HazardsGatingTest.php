<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Models\User;
use App\Modules\Hazards\Filament\Resources\HazardResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Hazards Co',
        'slug' => 'hazards-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'hazards@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers hazards as a non-core pro module depending on clients_sites', function (): void {
    $module = Module::query()->where('key', 'hazards')->firstOrFail();
    expect($module->is_core)->toBeFalse();
    expect($module->plan_tier)->toBe('pro');
    expect($module->dependencyKeys())->toContain('clients_sites');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'hazards'))->toBeFalse();
});

it('gates Filament HazardResource when module is off', function (): void {
    expect(HazardResource::canAccess())->toBeFalse();
});

it('gates the hazards HTTP route when module is off then on', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('hazards.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'hazards');

    $this->actingAs($this->user)
        ->get(route('hazards.show'))
        ->assertOk()
        ->assertJsonPath('module', 'hazards');
});
