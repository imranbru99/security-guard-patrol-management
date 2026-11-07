<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Models\User;
use App\Modules\PatrolMonitoring\Filament\Pages\PatrolMonitoringPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Monitoring Co',
        'slug' => 'monitoring-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'monitoring@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers patrol_monitoring as a non-core pro module depending on patrol_execution', function (): void {
    $module = Module::query()->where('key', 'patrol_monitoring')->firstOrFail();
    expect($module->is_core)->toBeFalse();
    expect($module->plan_tier)->toBe('pro');
    expect($module->dependencyKeys())->toContain('patrol_execution');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'patrol_monitoring'))->toBeFalse();
});

it('gates Filament PatrolMonitoringPage when module is off', function (): void {
    expect(PatrolMonitoringPage::canAccess())->toBeFalse();
});

it('gates the patrol_monitoring HTTP route when module is off then on', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('patrol-monitoring.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'patrol_monitoring');

    $this->actingAs($this->user)
        ->get(route('patrol-monitoring.show'))
        ->assertOk()
        ->assertJsonPath('module', 'patrol_monitoring');
});
