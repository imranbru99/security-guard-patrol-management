<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Models\User;
use App\Modules\Alarms\Filament\Resources\AlarmResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Alarms Co',
        'slug' => 'alarms-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'alarms@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers alarms as a non-core enterprise module depending on control_room', function (): void {
    $module = Module::query()->where('key', 'alarms')->firstOrFail();
    expect($module->is_core)->toBeFalse();
    expect($module->plan_tier)->toBe('enterprise');
    expect($module->dependencyKeys())->toContain('control_room');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'alarms'))->toBeFalse();
});

it('gates Filament AlarmResource when module is off', function (): void {
    expect(AlarmResource::canAccess())->toBeFalse();
});

it('gates the alarms HTTP route when module is off then on', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('alarms.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'alarms');

    $this->actingAs($this->user)
        ->get(route('alarms.show'))
        ->assertOk()
        ->assertJsonPath('module', 'alarms');
});
