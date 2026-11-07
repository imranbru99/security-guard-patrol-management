<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Core\Tenancy\TenantContext;
use App\Models\User;
use App\Modules\SettingsForms\Filament\Pages\FormBuilderPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Forms Co',
        'slug' => 'forms-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'forms@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('keeps settings_forms enabled as a core module after sync', function (): void {
    $manager = app(ModuleManager::class);

    expect(Module::query()->where('key', 'settings_forms')->where('is_core', true)->exists())->toBeTrue();
    expect($manager->enabled($this->tenant, 'settings_forms'))->toBeTrue();
});

it('serves the gated settings_forms route when tenant is set', function (): void {
    $this->actingAs($this->user)
        ->get(route('settings-forms.show'))
        ->assertOk()
        ->assertJsonPath('module', 'settings_forms');
});

it('allows FormBuilderPage access when tenant context is set', function (): void {
    TenantContext::set($this->tenant);
    expect(FormBuilderPage::canAccess())->toBeTrue();
    TenantContext::clear();
    expect(FormBuilderPage::canAccess())->toBeFalse();
});
