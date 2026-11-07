<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Licences Co',
        'slug' => 'licences-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'licences@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers licences as a non-core starter module depending on guards', function (): void {
    $module = Module::query()->where('key', 'licences')->firstOrFail();
    expect($module->is_core)->toBeFalse();
    expect($module->plan_tier)->toBe('starter');
    expect($module->dependencyKeys())->toContain('guards');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'licences'))->toBeFalse();
});

it('gates the licences HTTP route when module is off', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('licences.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'licences');

    $this->actingAs($this->user)
        ->get(route('licences.show'))
        ->assertOk()
        ->assertJsonPath('module', 'licences');
});
