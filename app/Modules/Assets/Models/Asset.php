<?php

declare(strict_types=1);

namespace App\Modules\Assets\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\ClientsSites\Models\Site;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Asset extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'tenant_id',
        'site_id',
        'asset_tag',
        'name',
        'category',
        'status',
        'serial_number',
        'qr_code',
        'is_armed_equipment',
        'licence_permit_number',
        'licence_expires_on',
        'daily_issue_count',
        'daily_return_count',
        'custody_discrepancy',
        'custody_holder',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'is_armed_equipment' => 'boolean',
            'licence_expires_on' => 'date',
            'daily_issue_count' => 'integer',
            'daily_return_count' => 'integer',
            'custody_discrepancy' => 'boolean',
            'meta' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Asset $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /** @return BelongsTo<Site, $this> */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /** @return HasMany<AssetAssignment, $this> */
    public function assignments(): HasMany
    {
        return $this->hasMany(AssetAssignment::class);
    }

    /** @return HasMany<AssetMaintenance, $this> */
    public function maintenances(): HasMany
    {
        return $this->hasMany(AssetMaintenance::class);
    }
}
