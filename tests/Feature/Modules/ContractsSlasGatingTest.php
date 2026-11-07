<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Models\User;
use App\Modules\ContractsSlas\Filament\Resources\ContractResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Contracts Co',
        'slug' => 'contracts-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'contracts@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers contracts_slas as a non-core starter module depending on clients_sites', function (): void {
    $module = Module::query()->where('key', 'contracts_slas')->firstOrFail();
    expect($module->is_core)->toBeFalse();
    expect($module->plan_tier)->toBe('starter');
    expect($module->depends_on)->toContain('clients_sites');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'contracts_slas'))->toBeFalse();
});

it('gates Filament ContractResource when module is off', function (): void {
    expect(ContractResource::canAccess())->toBeFalse();
});

it('enables contracts_slas with clients_sites dependency and gates the HTTP route', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('contracts-slas.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'contracts_slas');

    expect($manager->enabled($this->tenant, 'clients_sites'))->toBeTrue();
    expect($manager->enabled($this->tenant, 'contracts_slas'))->toBeTrue();

    $this->actingAs($this->user)
        ->get(route('contracts-slas.show'))
        ->assertOk()
        ->assertJsonPath('module', 'contracts_slas');
});
