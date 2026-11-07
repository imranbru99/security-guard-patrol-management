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
        'name' => 'Guards Co',
        'slug' => 'guards-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'guards@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers guards as a non-core starter module', function (): void {
    expect(Module::query()->where('key', 'guards')->where('is_core', false)->exists())->toBeTrue();
    expect(Module::query()->where('key', 'guards')->value('plan_tier'))->toBe('starter');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'guards'))->toBeFalse();
});

it('gates the guards HTTP route when module is off', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('guards.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'guards');

    $this->actingAs($this->user)
        ->get(route('guards.show'))
        ->assertOk()
        ->assertJsonPath('module', 'guards');
});

it('gates the guards API list when module is off then on', function (): void {
    $manager = app(ModuleManager::class);
    $token = $this->user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->getJson(route('api.guards.index'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'guards');

    $this->withToken($token)
        ->getJson(route('api.guards.index'))
        ->assertOk()
        ->assertJsonStructure(['data']);
});
