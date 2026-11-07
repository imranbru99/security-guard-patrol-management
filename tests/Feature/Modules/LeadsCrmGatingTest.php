<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Models\User;
use App\Modules\LeadsCrm\Filament\Resources\LeadResource;
use App\Modules\LeadsCrm\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Leads Co',
        'slug' => 'leads-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
        'is_active' => true,
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'leads@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers leads_crm as a non-core starter module', function (): void {
    $module = Module::query()->where('key', 'leads_crm')->firstOrFail();
    expect($module->is_core)->toBeFalse();
    expect($module->plan_tier)->toBe('starter');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'leads_crm'))->toBeFalse();
});

it('gates Filament LeadResource when module is off', function (): void {
    expect(LeadResource::canAccess())->toBeFalse();
});

it('gates the leads_crm HTTP route when module is off then on', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('leads-crm.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'leads_crm');

    $this->actingAs($this->user)
        ->get(route('leads-crm.show'))
        ->assertOk()
        ->assertJsonPath('module', 'leads_crm');
});

it('accepts public quote-request only when leads_crm is enabled for the tenant', function (): void {
    $payload = [
        'tenant' => 'leads-co',
        'name' => 'Prospect Person',
        'email' => 'prospect@example.com',
        'company' => 'Prospect LLC',
        'message' => 'Need weekend coverage',
    ];

    $this->postJson(route('leads.quote-request'), $payload)
        ->assertNotFound();

    expect(Lead::query()->count())->toBe(0);

    app(ModuleManager::class)->enable($this->tenant, 'leads_crm');

    $this->postJson(route('leads.quote-request'), $payload)
        ->assertCreated()
        ->assertJsonPath('module', 'leads_crm')
        ->assertJsonPath('status', 'received');

    expect(Lead::query()->where('email', 'prospect@example.com')->exists())->toBeTrue();
});
