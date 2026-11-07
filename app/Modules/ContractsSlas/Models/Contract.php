<?php

declare(strict_types=1);

namespace App\Modules\ContractsSlas\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\ClientsSites\Models\Client;
use App\Modules\ClientsSites\Models\Site;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Contract extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'client_id',
        'site_id',
        'name',
        'reference',
        'starts_at',
        'ends_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'date',
            'ends_at' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Contract $contract): void {
            if ($contract->getAttribute('ulid') === null || $contract->getAttribute('ulid') === '') {
                $contract->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /**
     * @return BelongsTo<Client, $this>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return BelongsTo<Site, $this>
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * @return HasMany<ContractRate, $this>
     */
    public function rates(): HasMany
    {
        return $this->hasMany(ContractRate::class);
    }

    /**
     * @return HasMany<Sla, $this>
     */
    public function slas(): HasMany
    {
        return $this->hasMany(Sla::class);
    }
}
