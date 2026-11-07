<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Models\User;
use App\Modules\Inspections\Filament\Resources\InspectionResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Inspect Co',
        'slug' => 'inspect-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'inspect@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers inspections as a non-core pro module', function (): void {
    $module = Module::query()->where('key', 'inspections')->firstOrFail();
    expect($module->is_core)->toBeFalse();
    expect($module->plan_tier)->toBe('pro');
    expect($module->dependencyKeys())->toContain('settings_forms');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'inspections'))->toBeFalse();
});

it('gates Filament InspectionResource when module is off', function (): void {
    expect(InspectionResource::canAccess())->toBeFalse();
});

it('gates the inspections HTTP route when module is off then on', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('inspections.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'inspections');

    $this->actingAs($this->user)
        ->get(route('inspections.show'))
        ->assertOk()
        ->assertJsonPath('module', 'inspections');
});
