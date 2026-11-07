<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Models\User;
use App\Modules\Emergency\Filament\Resources\EmergencyPlanResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Emergency Co',
        'slug' => 'emergency-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'emergency@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers emergency as a non-core enterprise module depending on clients_sites', function (): void {
    $module = Module::query()->where('key', 'emergency')->firstOrFail();
    expect($module->is_core)->toBeFalse();
    expect($module->plan_tier)->toBe('enterprise');
    expect($module->dependencyKeys())->toContain('clients_sites');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'emergency'))->toBeFalse();
});

it('gates Filament EmergencyPlanResource when module is off', function (): void {
    expect(EmergencyPlanResource::canAccess())->toBeFalse();
});

it('gates the emergency HTTP route when module is off then on', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('emergency.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'emergency');

    $this->actingAs($this->user)
        ->get(route('emergency.show'))
        ->assertOk()
        ->assertJsonPath('module', 'emergency');
});
