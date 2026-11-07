<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Models\User;
use App\Modules\ClientsSites\Filament\Resources\ClientResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Clients Co',
        'slug' => 'clients-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'clients@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers clients_sites as a non-core starter module', function (): void {
    $module = Module::query()->where('key', 'clients_sites')->firstOrFail();
    expect($module->is_core)->toBeFalse();
    expect($module->plan_tier)->toBe('starter');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'clients_sites'))->toBeFalse();
});

it('gates Filament ClientResource when module is off', function (): void {
    expect(ClientResource::canAccess())->toBeFalse();
});

it('gates the clients_sites HTTP route when module is off then on', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('clients-sites.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'clients_sites');

    $this->actingAs($this->user)
        ->get(route('clients-sites.show'))
        ->assertOk()
        ->assertJsonPath('module', 'clients_sites');
});
