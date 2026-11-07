<?php

declare(strict_types=1);

namespace App\Modules\LeadsCrm\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Lead extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'name',
        'email',
        'phone',
        'company',
        'source',
        'status',
        'message',
    ];

    protected static function booted(): void
    {
        static::creating(function (Lead $lead): void {
            if ($lead->getAttribute('ulid') === null || $lead->getAttribute('ulid') === '') {
                $lead->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /**
     * @return HasMany<Quote, $this>
     */
    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }
}
