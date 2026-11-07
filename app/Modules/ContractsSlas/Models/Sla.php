<?php

declare(strict_types=1);

namespace App\Modules\ContractsSlas\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Sla extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'contract_id',
        'name',
        'target_metric',
        'target_value',
    ];

    protected function casts(): array
    {
        return [
            'target_value' => 'float',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Sla $sla): void {
            if ($sla->getAttribute('ulid') === null || $sla->getAttribute('ulid') === '') {
                $sla->setAttribute('ulid', (string) Str::ulid());
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

    /**
     * @return HasMany<SlaScore, $this>
     */
    public function scores(): HasMany
    {
        return $this->hasMany(SlaScore::class);
    }
}
