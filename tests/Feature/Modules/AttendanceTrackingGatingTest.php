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
        'name' => 'Attendance Co',
        'slug' => 'attendance-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'attendance@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers attendance as a non-core starter module', function (): void {
    expect(Module::query()->where('key', 'attendance')->where('is_core', false)->exists())->toBeTrue();
    expect(Module::query()->where('key', 'attendance')->value('plan_tier'))->toBe('starter');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'attendance'))->toBeFalse();
});

it('gates the attendance HTTP route when module is off', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('attendance.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'attendance');

    $this->actingAs($this->user)
        ->get(route('attendance.show'))
        ->assertOk()
        ->assertJsonPath('module', 'attendance');
});

it('gates the virtual check-in API when module is off then on', function (): void {
    $manager = app(ModuleManager::class);
    $token = $this->user->createToken('test')->plainTextToken;

    $payload = [
        'employee_id' => 'E-100',
        'lat' => 23.8103,
        'lng' => 90.4125,
    ];

    $this->withToken($token)
        ->postJson(route('api.attendance.virtual-checkin'), $payload)
        ->assertNotFound();

    $manager->enable($this->tenant, 'attendance');

    $this->withToken($token)
        ->postJson(route('api.attendance.virtual-checkin'), $payload)
        ->assertCreated()
        ->assertJsonPath('success', true);
});
