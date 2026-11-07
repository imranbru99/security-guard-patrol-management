<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Models\User;
use App\Modules\Incidents\Filament\Resources\IncidentResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Incidents Co',
        'slug' => 'incidents-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'incidents@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers incidents as a non-core starter module depending on clients_sites and media_vault', function (): void {
    $module = Module::query()->where('key', 'incidents')->firstOrFail();
    expect($module->is_core)->toBeFalse();
    expect($module->plan_tier)->toBe('starter');
    expect($module->dependencyKeys())->toContain('clients_sites');
    expect($module->dependencyKeys())->toContain('media_vault');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'incidents'))->toBeFalse();
});

it('gates Filament IncidentResource when module is off', function (): void {
    expect(IncidentResource::canAccess())->toBeFalse();
});

it('gates the incidents HTTP route when module is off then on', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('incidents.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'incidents');

    $this->actingAs($this->user)
        ->get(route('incidents.show'))
        ->assertOk()
        ->assertJsonPath('module', 'incidents');
});
