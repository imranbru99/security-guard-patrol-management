<?php

declare(strict_types=1);

namespace App\Modules\Billing\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class InvoiceLine extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'invoice_id',
        'description',
        'quantity',
        'unit_amount_cents',
        'amount_cents',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_amount_cents' => 'integer',
            'amount_cents' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (InvoiceLine $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /** @return BelongsTo<Invoice, $this> */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
