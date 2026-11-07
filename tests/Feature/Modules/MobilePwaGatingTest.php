<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'PWA Co',
        'slug' => 'pwa-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'pwa@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers mobile_pwa as a non-core starter module', function (): void {
    expect(Module::query()->where('key', 'mobile_pwa')->where('is_core', false)->exists())->toBeTrue();
    expect(Module::query()->where('key', 'mobile_pwa')->value('plan_tier'))->toBe('starter');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'mobile_pwa'))->toBeFalse();
});

it('gates the mobile_pwa HTTP route when module is off', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('mobile-pwa.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'mobile_pwa');

    $this->actingAs($this->user)
        ->get(route('mobile-pwa.show'))
        ->assertOk()
        ->assertJsonPath('module', 'mobile_pwa');
});

it('acknowledges offline sync with client_uuid', function (): void {
    app(ModuleManager::class)->enable($this->tenant, 'mobile_pwa');
    Sanctum::actingAs($this->user);

    $this->postJson(route('api.mobile.offline-sync'), [
        'client_uuid' => 'client-abc-123',
        'events' => [
            ['type' => 'patrol_scan', 'payload' => []],
        ],
    ])
        ->assertOk()
        ->assertJsonPath('acknowledged', true)
        ->assertJsonPath('client_uuid', 'client-abc-123')
        ->assertJsonPath('accepted', 1);
});

it('serves the web manifest', function (): void {
    $this->get('/manifest.webmanifest')
        ->assertOk();
});
