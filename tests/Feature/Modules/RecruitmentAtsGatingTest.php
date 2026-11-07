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
        'name' => 'Recruitment Co',
        'slug' => 'recruitment-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'recruitment@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers recruitment as a non-core pro module', function (): void {
    expect(Module::query()->where('key', 'recruitment')->where('is_core', false)->exists())->toBeTrue();
    expect(Module::query()->where('key', 'recruitment')->value('plan_tier'))->toBe('pro');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'recruitment'))->toBeFalse();
});

it('gates the recruitment HTTP route when module is off', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('recruitment.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'recruitment');

    $this->actingAs($this->user)
        ->get(route('recruitment.show'))
        ->assertOk()
        ->assertJsonPath('module', 'recruitment');
});