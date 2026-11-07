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
        'name' => 'Analytics Co',
        'slug' => 'analytics-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'analytics@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers analytics as a non-core pro module', function (): void {
    expect(Module::query()->where('key', 'analytics')->where('is_core', false)->exists())->toBeTrue();
    expect(Module::query()->where('key', 'analytics')->value('plan_tier'))->toBe('pro');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'analytics'))->toBeFalse();
});

it('gates the analytics HTTP route when module is off', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('analytics.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'analytics');

    $this->actingAs($this->user)
        ->get(route('analytics.show'))
        ->assertOk()
        ->assertJsonPath('module', 'analytics')
        ->assertJsonStructure(['metrics']);
});
