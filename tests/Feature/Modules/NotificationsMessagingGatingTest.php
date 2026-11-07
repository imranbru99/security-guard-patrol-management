<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Core\Tenancy\TenantContext;
use App\Models\User;
use App\Modules\NotificationsMessaging\Filament\Resources\NotificationTemplateResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Notify Co',
        'slug' => 'notify-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'notify@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('is always enabled as a core module without a tenant_modules row', function (): void {
    expect(Module::query()->where('key', 'notifications')->value('is_core'))->toBeTrue();
    expect(app(ModuleManager::class)->enabled($this->tenant, 'notifications'))->toBeTrue();
});

it('allows Filament resource access when tenant context is set', function (): void {
    TenantContext::set($this->tenant);
    expect(NotificationTemplateResource::canAccess())->toBeTrue();
    TenantContext::clear();
    expect(NotificationTemplateResource::canAccess())->toBeFalse();
});

it('serves gated notifications route when core module is on and 404 when temporarily off', function (): void {
    $this->actingAs($this->user)
        ->get(route('notifications.show'))
        ->assertOk()
        ->assertSee('Notifications & Messaging module', false);

    Module::query()->where('key', 'notifications')->update(['is_core' => false]);
    app(ModuleManager::class)->bustCache($this->tenant);

    $this->actingAs($this->user)
        ->get(route('notifications.show'))
        ->assertNotFound();
});
