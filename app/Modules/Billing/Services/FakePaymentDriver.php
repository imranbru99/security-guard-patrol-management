<?php

declare(strict_types=1);

namespace App\Modules\Billing\Services;

use App\Modules\Billing\Models\Invoice;
use App\Modules\Billing\Models\Payment;

final class FakePaymentDriver
{
    /**
     * @param  array<string, mixed>  $meta
     */
    public function charge(Invoice $invoice, int $amountCents, array $meta = []): Payment
    {
        return Payment::query()->create([
            'tenant_id' => $invoice->tenant_id,
            'invoice_id' => $invoice->id,
            'amount_cents' => $amountCents,
            'currency' => $invoice->currency,
            'driver' => 'fake',
            'status' => 'succeeded',
            'reference' => 'fake_'.bin2hex(random_bytes(4)),
            'paid_at' => now(),
            'meta' => $meta,
        ]);
    }
}
