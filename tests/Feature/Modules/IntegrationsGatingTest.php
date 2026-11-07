<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Models\User;
use App\Modules\Integrations\Services\FakeSmsDriver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Integrations Co',
        'slug' => 'integrations-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
        'is_active' => true,
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'integrations@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers integrations as a non-core pro module', function (): void {
    expect(Module::query()->where('key', 'integrations')->where('is_core', false)->exists())->toBeTrue();
    expect(Module::query()->where('key', 'integrations')->value('plan_tier'))->toBe('pro');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'integrations'))->toBeFalse();
});

it('gates the integrations HTTP route when module is off', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('integrations.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'integrations');

    $this->actingAs($this->user)
        ->get(route('integrations.show'))
        ->assertOk()
        ->assertJsonPath('module', 'integrations');
});

it('serves ICS feed when integrations is enabled', function (): void {
    $this->get(route('integrations.shifts.ics', ['tenantSlug' => 'integrations-co']))
        ->assertNotFound();

    app(ModuleManager::class)->enable($this->tenant, 'integrations');

    $this->get(route('integrations.shifts.ics', ['tenantSlug' => 'integrations-co']))
        ->assertOk()
        ->assertHeader('content-type', 'text/calendar; charset=utf-8')
        ->assertSee('BEGIN:VCALENDAR', false);
});

it('sends via FakeSmsDriver', function (): void {
    $driver = new FakeSmsDriver;
    $result = $driver->send('+10000000000', 'Hello');

    expect($result['ok'])->toBeTrue();
    expect($result['driver'])->toBe('fake');
    expect($driver->sent())->toHaveCount(1);
});
