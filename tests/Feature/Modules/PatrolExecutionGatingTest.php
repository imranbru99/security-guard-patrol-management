<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Models\User;
use App\Modules\PatrolExecution\Filament\Pages\MyPatrolPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Execution Co',
        'slug' => 'execution-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'execution@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers patrol_execution as a non-core starter module depending on patrol_routes', function (): void {
    $module = Module::query()->where('key', 'patrol_execution')->firstOrFail();
    expect($module->is_core)->toBeFalse();
    expect($module->plan_tier)->toBe('starter');
    expect($module->dependencyKeys())->toContain('patrol_routes');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'patrol_execution'))->toBeFalse();
});

it('gates Filament MyPatrolPage when module is off', function (): void {
    expect(MyPatrolPage::canAccess())->toBeFalse();
});

it('gates the patrol_execution HTTP route when module is off then on', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('patrol-execution.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'patrol_execution');

    $this->actingAs($this->user)
        ->get(route('patrol-execution.show'))
        ->assertOk()
        ->assertJsonPath('module', 'patrol_execution');
});
