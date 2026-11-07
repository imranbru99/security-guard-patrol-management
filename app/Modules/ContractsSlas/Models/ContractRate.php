<?php

declare(strict_types=1);

namespace App\Modules\ContractsSlas\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ContractRate extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'contract_id',
        'label',
        'amount_cents',
        'currency',
        'rate_type',
    ];

    protected function casts(): array
    {
        return [
            'amount_cents' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ContractRate $rate): void {
            if ($rate->getAttribute('ulid') === null || $rate->getAttribute('ulid') === '') {
                $rate->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /**
     * @return BelongsTo<Contract, $this>
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }
}
