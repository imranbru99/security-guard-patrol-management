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
        'name' => 'Handover Co',
        'slug' => 'handover-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'handover@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers handover as a non-core starter module', function (): void {
    expect(Module::query()->where('key', 'handover')->where('is_core', false)->exists())->toBeTrue();
    expect(Module::query()->where('key', 'handover')->value('plan_tier'))->toBe('starter');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'handover'))->toBeFalse();
});

it('gates the handover HTTP route when module is off', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('handover.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'handover');

    $this->actingAs($this->user)
        ->get(route('handover.show'))
        ->assertOk()
        ->assertJsonPath('module', 'handover');
});
