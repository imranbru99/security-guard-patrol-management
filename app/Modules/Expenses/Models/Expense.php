<?php

declare(strict_types=1);

namespace App\Modules\Expenses\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Expense extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid', 'tenant_id', 'vendor_id', 'purchase_order_id', 'title',
        'amount_cents', 'currency', 'status', 'category', 'incurred_on', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount_cents' => 'integer',
            'incurred_on' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Expense $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /** @return BelongsTo<Vendor, $this> */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /** @return BelongsTo<PurchaseOrder, $this> */
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}
