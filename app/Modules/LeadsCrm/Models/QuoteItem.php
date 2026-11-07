<?php

declare(strict_types=1);

namespace App\Modules\LeadsCrm\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class QuoteItem extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'quote_id',
        'description',
        'quantity',
        'amount_cents',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'amount_cents' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (QuoteItem $item): void {
            if ($item->getAttribute('ulid') === null || $item->getAttribute('ulid') === '') {
                $item->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /**
     * @return BelongsTo<Quote, $this>
     */
    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }
}
