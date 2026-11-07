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
        'name' => 'Leave Co',
        'slug' => 'leave-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'leave@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers leave as a non-core starter module', function (): void {
    expect(Module::query()->where('key', 'leave')->where('is_core', false)->exists())->toBeTrue();
    expect(Module::query()->where('key', 'leave')->value('plan_tier'))->toBe('starter');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'leave'))->toBeFalse();
});

it('gates the leave HTTP route when module is off', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('leave.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'leave');

    $this->actingAs($this->user)
        ->get(route('leave.show'))
        ->assertOk()
        ->assertJsonPath('module', 'leave');
});