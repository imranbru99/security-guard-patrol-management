<?php

declare(strict_types=1);

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\Tenant;
use App\Core\Tenancy\TenantContext;
use App\Models\User;
use App\Modules\Billing\Models\Invoice;
use App\Modules\Billing\Services\FakePaymentDriver;
use App\Modules\Billing\Services\InvoicePdfService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use ImranDev\UnicodePdf\Facades\UnicodePdf;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->artisan('modules:sync')->assertSuccessful();

    $this->tenant = Tenant::query()->create([
        'name' => 'Billing Co',
        'slug' => 'billing-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $this->user = User::factory()->create([
        'tenant_id' => $this->tenant->id,
        'email' => 'billing@test.demo',
        'password' => Hash::make('password'),
    ]);
});

it('registers billing as a non-core pro module', function (): void {
    expect(Module::query()->where('key', 'billing')->where('is_core', false)->exists())->toBeTrue();
    expect(Module::query()->where('key', 'billing')->value('plan_tier'))->toBe('pro');
    expect(app(ModuleManager::class)->enabled($this->tenant, 'billing'))->toBeFalse();
});

it('gates the billing HTTP route when module is off', function (): void {
    $manager = app(ModuleManager::class);

    $this->actingAs($this->user)
        ->get(route('billing.show'))
        ->assertNotFound();

    $manager->enable($this->tenant, 'clients_sites');
    $manager->enable($this->tenant, 'contracts_slas');
    $manager->enable($this->tenant, 'billing');

    $this->actingAs($this->user)
        ->get(route('billing.show'))
        ->assertOk()
        ->assertJsonPath('module', 'billing');
});

it('records a fake payment and renders invoice pdf with UnicodePdf::fake', function (): void {
    TenantContext::set($this->tenant);

    if (method_exists(UnicodePdf::class, 'fake')) {
        UnicodePdf::fake();
    }

    $invoice = Invoice::query()->create([
        'tenant_id' => $this->tenant->id,
        'number' => 'INV-TEST-1',
        'status' => 'approved',
        'currency' => 'USD',
        'total_cents' => 15000,
    ]);

    $payment = app(FakePaymentDriver::class)->charge($invoice, 15000);
    $pdf = app(InvoicePdfService::class)->render($invoice);

    TenantContext::clear();

    expect($payment->driver)->toBe('fake');
    expect($payment->amount_cents)->toBe(15000);
    expect($pdf['ok'])->toBeTrue();
    expect($pdf['filename'])->toContain('INV-TEST-1');
});
