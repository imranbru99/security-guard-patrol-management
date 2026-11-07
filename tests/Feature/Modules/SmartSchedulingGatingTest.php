<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Core\Tenancy\TenantContext;
use App\Models\User;
use App\Modules\SmartScheduling\Services\CoverageGapPredictor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Forecast Co',
        'slug' => 'forecast-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'forecast@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers smart_scheduling as a non-core addon module', function (): void {
    expect(Module::query()->where('key', 'smart_scheduling')->where('is_core', false)->exists())->toBeTrue();
    expect(Module::query()->where('key', 'smart_scheduling')->value('plan_tier'))->toBe('addon');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'smart_scheduling'))->toBeFalse();
});

it('gates the smart_scheduling HTTP route when module is off', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('smart-scheduling.show'))
        ->assertNotFound();

    // depends_on shift_scheduling — enable dependency first
    $manager->enable($this->tenant, 'shift_scheduling');
    $manager->enable($this->tenant, 'smart_scheduling');

    $this->actingAs($this->user)
        ->get(route('smart-scheduling.show'))
        ->assertOk()
        ->assertJsonPath('module', 'smart_scheduling');
});

it('runs CoverageGapPredictor and stores a ForecastRun', function (): void {
    TenantContext::set($this->tenant);
    $run = app(CoverageGapPredictor::class)->run($this->tenant, '7d');
    TenantContext::clear();

    expect($run->status)->toBe('completed');
    expect($run->summary)->toHaveKey('gap_count');
});
