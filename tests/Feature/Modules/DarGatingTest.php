<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Models\User;
use App\Modules\Dar\Filament\Resources\DarResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Dar Co',
        'slug' => 'dar-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'dar@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers dar as a non-core starter module depending on shift_scheduling', function (): void {
    $module = Module::query()->where('key', 'dar')->firstOrFail();
    expect($module->is_core)->toBeFalse();
    expect($module->plan_tier)->toBe('starter');
    expect($module->dependencyKeys())->toContain('shift_scheduling');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'dar'))->toBeFalse();
});

it('gates Filament DarResource when module is off', function (): void {
    expect(DarResource::canAccess())->toBeFalse();
});

it('gates the dar HTTP route when module is off then on', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('dar.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'dar');

    $this->actingAs($this->user)
        ->get(route('dar.show'))
        ->assertOk()
        ->assertJsonPath('module', 'dar');
});
