<?php

declare(strict_types=1);

namespace App\Modules\LeadsCrm\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\ClientsSites\Models\Client;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Quote extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'lead_id',
        'client_id',
        'title',
        'status',
        'currency',
    ];

    protected static function booted(): void
    {
        static::creating(function (Quote $quote): void {
            if ($quote->getAttribute('ulid') === null || $quote->getAttribute('ulid') === '') {
                $quote->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /**
     * @return BelongsTo<Lead, $this>
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * @return BelongsTo<Client, $this>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return HasMany<QuoteItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }
}
