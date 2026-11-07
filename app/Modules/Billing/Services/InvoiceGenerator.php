<?php

declare(strict_types=1);

namespace App\Modules\Billing\Services;

use App\Core\Tenancy\Tenant;
use App\Modules\Billing\Models\Invoice;
use App\Modules\Billing\Models\InvoiceLine;
use App\Modules\ContractsSlas\Models\Contract;
use App\Modules\ContractsSlas\Models\ContractRate;

/**
 * Stub: generate a draft invoice from contract rates (amount_cents).
 */
final class InvoiceGenerator
{
    public function fromContract(Tenant $tenant, Contract $contract): Invoice
    {
        $invoice = Invoice::query()->create([
            'tenant_id' => $tenant->id,
            'client_id' => $contract->client_id,
            'contract_id' => $contract->id,
            'number' => 'INV-'.strtoupper(substr((string) $contract->ulid, -8)),
            'status' => 'draft',
            'currency' => $tenant->currency ?: 'USD',
            'issued_on' => now()->toDateString(),
            'due_on' => now()->addDays(14)->toDateString(),
        ]);

        $subtotal = 0;
        $rates = ContractRate::query()->where('contract_id', $contract->id)->get();
        if ($rates->isEmpty()) {
            InvoiceLine::query()->create([
                'tenant_id' => $tenant->id,
                'invoice_id' => $invoice->id,
                'description' => 'Contract services (stub)',
                'quantity' => 1,
                'unit_amount_cents' => 0,
                'amount_cents' => 0,
            ]);
        } else {
            foreach ($rates as $rate) {
                $amount = (int) $rate->amount_cents;
                $subtotal += $amount;
                InvoiceLine::query()->create([
                    'tenant_id' => $tenant->id,
                    'invoice_id' => $invoice->id,
                    'description' => (string) ($rate->label ?: 'Rate'),
                    'quantity' => 1,
                    'unit_amount_cents' => $amount,
                    'amount_cents' => $amount,
                ]);
            }
        }

        $invoice->update([
            'subtotal_cents' => $subtotal,
            'tax_cents' => 0,
            'total_cents' => $subtotal,
        ]);

        return $invoice->fresh(['lines']) ?? $invoice;
    }
}
