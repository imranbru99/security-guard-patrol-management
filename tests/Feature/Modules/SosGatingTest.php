<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Models\User;
use App\Modules\Sos\Filament\Pages\SosControlRoomPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Sos Co',
        'slug' => 'sos-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'sos@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers sos as a non-core pro module depending on guards', function (): void {
    $module = Module::query()->where('key', 'sos')->firstOrFail();
    expect($module->is_core)->toBeFalse();
    expect($module->plan_tier)->toBe('pro');
    expect($module->dependencyKeys())->toContain('guards');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'sos'))->toBeFalse();
});

it('gates Filament SosControlRoomPage when module is off', function (): void {
    expect(SosControlRoomPage::canAccess())->toBeFalse();
});

it('gates the sos HTTP route when module is off then on', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('sos.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'sos');

    $this->actingAs($this->user)
        ->get(route('sos.show'))
        ->assertOk()
        ->assertJsonPath('module', 'sos');
});
