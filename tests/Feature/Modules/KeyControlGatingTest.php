<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Models\User;
use App\Modules\KeyControl\Filament\Resources\KeyItemResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Keys Co',
        'slug' => 'keys-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'keys@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers key_control as a non-core starter module', function (): void {
    $module = Module::query()->where('key', 'key_control')->firstOrFail();
    expect($module->is_core)->toBeFalse();
    expect($module->plan_tier)->toBe('starter');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'key_control'))->toBeFalse();
});

it('gates Filament KeyItemResource when module is off', function (): void {
    expect(KeyItemResource::canAccess())->toBeFalse();
});

it('gates the key_control HTTP route when module is off then on', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('key-control.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'key_control');

    $this->actingAs($this->user)
        ->get(route('key-control.show'))
        ->assertOk()
        ->assertJsonPath('module', 'key_control');
});
