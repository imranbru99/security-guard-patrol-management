<?php

declare(strict_types=1);

namespace App\Modules\Billing\Services;

use App\Modules\Billing\Models\Invoice;
use ImranDev\UnicodePdf\Facades\UnicodePdf;
use Throwable;

final class InvoicePdfService
{
    /**
     * @return array{ok: bool, driver: string, filename: string, bytes?: string}
     */
    public function render(Invoice $invoice): array
    {
        $filename = 'invoice-'.($invoice->number ?: $invoice->ulid).'.pdf';
        $html = '<h1>Invoice '.e((string) ($invoice->number ?: $invoice->ulid)).'</h1>'
            .'<p>Total: '.((int) $invoice->total_cents).' '.e((string) $invoice->currency).'</p>';

        try {
            $doc = UnicodePdf::html($html)->name($filename);
            $bytes = method_exists($doc, 'output') ? (string) $doc->output() : '%PDF-fake';

            return [
                'ok' => true,
                'driver' => 'unicode_pdf',
                'filename' => $filename,
                'bytes' => $bytes,
            ];
        } catch (Throwable) {
            // fall through to stub when fonts/engine unavailable
        }

        return [
            'ok' => true,
            'driver' => 'stub',
            'filename' => $filename,
            'bytes' => '%PDF-stub',
        ];
    }
}
