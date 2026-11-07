<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Core\Tenancy\TenantContext;
use App\Models\User;
use App\Modules\MediaVault\Filament\Resources\MediaAssetResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Media Co',
        'slug' => 'media-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'media@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('keeps media_vault enabled as a core module after sync', function (): void {
    $manager = app(ModuleManager::class);

    expect(Module::query()->where('key', 'media_vault')->where('is_core', true)->exists())->toBeTrue();
    expect($manager->enabled($this->tenant, 'media_vault'))->toBeTrue();
});

it('serves the gated media_vault route when tenant is set', function (): void {
    $this->actingAs($this->user)
        ->get(route('media-vault.show'))
        ->assertOk()
        ->assertJsonPath('module', 'media_vault');
});

it('allows MediaAssetResource access when tenant context is set', function (): void {
    TenantContext::set($this->tenant);
    expect(MediaAssetResource::canAccess())->toBeTrue();
    TenantContext::clear();
    expect(MediaAssetResource::canAccess())->toBeFalse();
});
