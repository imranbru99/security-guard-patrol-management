<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Models\User;
use App\Modules\PatrolRoutes\Filament\Resources\PatrolRouteResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Routes Co',
        'slug' => 'routes-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'routes@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers patrol_routes as a non-core starter module depending on checkpoints', function (): void {
    $module = Module::query()->where('key', 'patrol_routes')->firstOrFail();
    expect($module->is_core)->toBeFalse();
    expect($module->plan_tier)->toBe('starter');
    expect($module->dependencyKeys())->toContain('checkpoints');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'patrol_routes'))->toBeFalse();
});

it('gates Filament PatrolRouteResource when module is off', function (): void {
    expect(PatrolRouteResource::canAccess())->toBeFalse();
});

it('gates the patrol_routes HTTP route when module is off then on', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('patrol-routes.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'patrol_routes');

    $this->actingAs($this->user)
        ->get(route('patrol-routes.show'))
        ->assertOk()
        ->assertJsonPath('module', 'patrol_routes');
});
