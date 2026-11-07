<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Models\User;
use App\Modules\CheckpointsTags\Filament\Resources\CheckpointResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Checkpoints Co',
        'slug' => 'checkpoints-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'checkpoints@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers checkpoints as a non-core starter module depending on clients_sites', function (): void {
    $module = Module::query()->where('key', 'checkpoints')->firstOrFail();
    expect($module->is_core)->toBeFalse();
    expect($module->plan_tier)->toBe('starter');
    expect($module->dependencyKeys())->toContain('clients_sites');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'checkpoints'))->toBeFalse();
});

it('gates Filament CheckpointResource when module is off', function (): void {
    expect(CheckpointResource::canAccess())->toBeFalse();
});

it('gates the checkpoints HTTP route when module is off then on', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('checkpoints.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'checkpoints');

    $this->actingAs($this->user)
        ->get(route('checkpoints.show'))
        ->assertOk()
        ->assertJsonPath('module', 'checkpoints');
});
