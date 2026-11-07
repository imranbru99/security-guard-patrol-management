<?php

declare(strict_types=1);

namespace App\Modules\CheckpointsTags\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\ClientsSites\Models\Site;
use App\Modules\ClientsSites\Models\SiteZone;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Checkpoint extends TenantModel
{
    public const VERIFICATION_TYPES = ['qr', 'nfc', 'gps', 'ble'];

    protected $fillable = [
        'ulid',
        'tenant_id',
        'site_id',
        'site_zone_id',
        'name',
        'code',
        'verification_type',
        'latitude',
        'longitude',
        'radius_meters',
        'nfc_uid',
        'ble_uuid',
        'qr_payload',
        'requires_photo',
        'requires_notes',
        'hazard_prompt',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'radius_meters' => 'integer',
            'requires_photo' => 'boolean',
            'requires_notes' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Checkpoint $m): void {
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

    /** @return BelongsTo<SiteZone, $this> */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(SiteZone::class, 'site_zone_id');
    }

    /** @return HasMany<CheckpointTag, $this> */
    public function tags(): HasMany
    {
        return $this->hasMany(CheckpointTag::class);
    }
}
