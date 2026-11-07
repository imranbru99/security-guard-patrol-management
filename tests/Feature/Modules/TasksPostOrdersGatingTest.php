<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Models\User;
use App\Modules\TasksPostOrders\Filament\Resources\TaskItemResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Tasks Co',
        'slug' => 'tasks-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'tasks@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers tasks_post_orders as a non-core starter module depending on handover', function (): void {
    $module = Module::query()->where('key', 'tasks_post_orders')->firstOrFail();
    expect($module->is_core)->toBeFalse();
    expect($module->plan_tier)->toBe('starter');
    expect($module->dependencyKeys())->toContain('handover');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'tasks_post_orders'))->toBeFalse();
});

it('gates Filament TaskItemResource when module is off', function (): void {
    expect(TaskItemResource::canAccess())->toBeFalse();
});

it('gates the tasks_post_orders HTTP route when module is off then on', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('tasks-post-orders.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'tasks_post_orders');

    $this->actingAs($this->user)
        ->get(route('tasks-post-orders.show'))
        ->assertOk()
        ->assertJsonPath('module', 'tasks_post_orders');
});
