<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Core\Tenancy\TenantContext;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Test Security Co',
        'slug' => 'test-security',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'owner@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('enables and disables the demo_toggle module for a tenant', function (): void {
    /** @var ModuleManager $manager */
    $manager = app(ModuleManager::class);

    expect(Module::query()->where('key', 'demo_toggle')->exists())->toBeTrue();
    expect($manager->enabled($this->tenant, 'demo_toggle'))->toBeFalse();

    $manager->enable($this->tenant, 'demo_toggle');
    expect($manager->enabled($this->tenant, 'demo_toggle'))->toBeTrue();

    $manager->disable($this->tenant, 'demo_toggle');
    expect($manager->enabled($this->tenant, 'demo_toggle'))->toBeFalse();
});

it('gates the demo toggle HTTP route when module is off', function (): void {
    /** @var ModuleManager $manager */
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('demo-toggle.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'demo_toggle');

    $this->actingAs($this->user)
        ->get(route('demo-toggle.show'))
        ->assertOk()
        ->assertSee('Demo Toggle module');
});

it('gates the demo toggle API when module is off', function (): void {
    /** @var ModuleManager $manager */
    $manager = app(ModuleManager::class);

    $token = $this->user->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->getJson(route('api.demo-toggle.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'demo_toggle');

    $this->withToken($token)
        ->getJson(route('api.demo-toggle.show'))
        ->assertOk()
        ->assertJsonPath('module', 'demo_toggle');
});

it('exposes blade @module directive based on tenant context', function (): void {
    $manager = app(ModuleManager::class);

    TenantContext::set($this->tenant);
    $off = Blade::render('@module("demo_toggle") ON @else OFF @endmodule');
    expect(trim($off))->toBe('OFF');

    $manager->enable($this->tenant, 'demo_toggle');
    $on = Blade::render('@module("demo_toggle") ON @else OFF @endmodule');
    expect(trim($on))->toBe('ON');

    TenantContext::clear();
});
