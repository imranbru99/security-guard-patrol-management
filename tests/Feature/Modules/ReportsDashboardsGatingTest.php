<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Core\Tenancy\TenantContext;
use App\Models\User;
use App\Modules\ReportsDashboards\Filament\Pages\ReportBuilderPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Reports Co',
        'slug' => 'reports-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'reports@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('keeps reports enabled as a core module after sync', function (): void {
    $manager = app(ModuleManager::class);

    expect(Module::query()->where('key', 'reports')->where('is_core', true)->exists())->toBeTrue();
    expect($manager->enabled($this->tenant, 'reports'))->toBeTrue();
});

it('serves the gated reports route when tenant is set', function (): void {
    $this->actingAs($this->user)
        ->get(route('reports.show'))
        ->assertOk()
        ->assertJsonPath('module', 'reports');
});

it('allows ReportBuilderPage access when tenant context is set', function (): void {
    TenantContext::set($this->tenant);
    expect(ReportBuilderPage::canAccess())->toBeTrue();
    TenantContext::clear();
    expect(ReportBuilderPage::canAccess())->toBeFalse();
});
