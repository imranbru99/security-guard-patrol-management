<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Models\User;
use App\Modules\ControlRoom\Filament\Pages\LiveWallPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Control Co',
        'slug' => 'control-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'control@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers control_room as a non-core pro module depending on sos', function (): void {
    $module = Module::query()->where('key', 'control_room')->firstOrFail();
    expect($module->is_core)->toBeFalse();
    expect($module->plan_tier)->toBe('pro');
    expect($module->dependencyKeys())->toContain('sos');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'control_room'))->toBeFalse();
});

it('gates Filament LiveWallPage when module is off', function (): void {
    expect(LiveWallPage::canAccess())->toBeFalse();
});

it('gates the control_room HTTP route when module is off then on', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('control-room.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'control_room');

    $this->actingAs($this->user)
        ->get(route('control-room.show'))
        ->assertOk()
        ->assertJsonPath('module', 'control_room');
});
