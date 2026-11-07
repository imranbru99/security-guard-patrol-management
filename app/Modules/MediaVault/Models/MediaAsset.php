<?php

declare(strict_types=1);

namespace App\Modules\MediaVault\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class MediaAsset extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'disk',
        'path',
        'filename',
        'mime',
        'size',
        'sha256',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'meta' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (MediaAsset $asset): void {
            if (empty($asset->getAttribute('ulid'))) {
                $asset->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /**
     * @return HasMany<EvidenceCustodyEvent, $this>
     */
    public function custodyEvents(): HasMany
    {
        return $this->hasMany(EvidenceCustodyEvent::class)->orderBy('id');
    }
}
