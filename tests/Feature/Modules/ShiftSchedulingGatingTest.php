<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Shift Co',
        'slug' => 'shift-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'shifts@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers shift_scheduling as a non-core starter module', function (): void {
    expect(Module::query()->where('key', 'shift_scheduling')->where('is_core', false)->exists())->toBeTrue();
    expect(Module::query()->where('key', 'shift_scheduling')->value('plan_tier'))->toBe('starter');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'shift_scheduling'))->toBeFalse();
});

it('gates the shift_scheduling HTTP route when module is off', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('shift_scheduling.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'shift_scheduling');

    $this->actingAs($this->user)
        ->get(route('shift_scheduling.show'))
        ->assertOk()
        ->assertJsonPath('module', 'shift_scheduling');
});

it('gates the shifts API list when module is off then on', function (): void {
    $manager = app(ModuleManager::class);
    $token = $this->user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->getJson(route('api.shifts.index'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'shift_scheduling');

    $this->withToken($token)
        ->getJson(route('api.shifts.index'))
        ->assertOk()
        ->assertJsonStructure(['data']);
});
