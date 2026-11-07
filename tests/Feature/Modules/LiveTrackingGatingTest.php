<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Models\User;
use App\Modules\LiveTracking\Filament\Pages\LiveTrackingPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Tracking Co',
        'slug' => 'tracking-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'tracking@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers live_tracking as a non-core pro module depending on patrol_execution', function (): void {
    $module = Module::query()->where('key', 'live_tracking')->firstOrFail();
    expect($module->is_core)->toBeFalse();
    expect($module->plan_tier)->toBe('pro');
    expect($module->dependencyKeys())->toContain('patrol_execution');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'live_tracking'))->toBeFalse();
});

it('gates Filament LiveTrackingPage when module is off', function (): void {
    expect(LiveTrackingPage::canAccess())->toBeFalse();
});

it('gates the live_tracking HTTP route when module is off then on', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('live-tracking.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'live_tracking');

    $this->actingAs($this->user)
        ->get(route('live-tracking.show'))
        ->assertOk()
        ->assertJsonPath('module', 'live_tracking');
});
