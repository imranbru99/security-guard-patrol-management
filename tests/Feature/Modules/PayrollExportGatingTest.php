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
        'name' => 'Payroll Co',
        'slug' => 'payroll-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'payroll@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers payroll_export as a non-core pro module', function (): void {
    expect(Module::query()->where('key', 'payroll_export')->where('is_core', false)->exists())->toBeTrue();
    expect(Module::query()->where('key', 'payroll_export')->value('plan_tier'))->toBe('pro');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'payroll_export'))->toBeFalse();
});

it('gates the payroll_export HTTP route when module is off', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('payroll-export.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'payroll_export');

    $this->actingAs($this->user)
        ->get(route('payroll-export.show'))
        ->assertOk()
        ->assertJsonPath('module', 'payroll_export');
});