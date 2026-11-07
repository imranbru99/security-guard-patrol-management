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
        'name' => 'Discipline Co',
        'slug' => 'discipline-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'discipline@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers discipline as a non-core pro module', function (): void {
    expect(Module::query()->where('key', 'discipline')->where('is_core', false)->exists())->toBeTrue();
    expect(Module::query()->where('key', 'discipline')->value('plan_tier'))->toBe('pro');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'discipline'))->toBeFalse();
});

it('gates the discipline HTTP route when module is off', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('discipline.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'discipline');

    $this->actingAs($this->user)
        ->get(route('discipline.show'))
        ->assertOk()
        ->assertJsonPath('module', 'discipline');
});