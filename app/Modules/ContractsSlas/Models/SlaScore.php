<?php

declare(strict_types=1);

namespace App\Modules\ContractsSlas\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class SlaScore extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'sla_id',
        'score',
        'scored_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'float',
            'scored_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (SlaScore $score): void {
            if ($score->getAttribute('ulid') === null || $score->getAttribute('ulid') === '') {
                $score->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /**
     * @return BelongsTo<Sla, $this>
     */
    public function sla(): BelongsTo
    {
        return $this->belongsTo(Sla::class);
    }
}
