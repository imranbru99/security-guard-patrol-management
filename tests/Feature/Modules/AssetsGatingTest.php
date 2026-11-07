<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Models\User;
use App\Modules\Assets\Filament\Resources\AssetResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Assets Co',
        'slug' => 'assets-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'assets@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers assets as a non-core pro module', function (): void {
    $module = Module::query()->where('key', 'assets')->firstOrFail();
    expect($module->is_core)->toBeFalse();
    expect($module->plan_tier)->toBe('pro');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'assets'))->toBeFalse();
});

it('gates Filament AssetResource when module is off', function (): void {
    expect(AssetResource::canAccess())->toBeFalse();
});

it('gates the assets HTTP route when module is off then on', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('assets.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'assets');

    $this->actingAs($this->user)
        ->get(route('assets.show'))
        ->assertOk()
        ->assertJsonPath('module', 'assets');
});
