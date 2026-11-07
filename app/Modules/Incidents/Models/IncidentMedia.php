<?php

declare(strict_types=1);

namespace App\Modules\Incidents\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\MediaVault\Models\MediaAsset;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class IncidentMedia extends TenantModel
{
    use SoftDeletes;

    protected $table = 'incident_media';

    protected $fillable = [
        'ulid',
        'tenant_id',
        'incident_id',
        'media_asset_id',
        'kind',
        'caption',
        'immutable',
        'immutable_note',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'immutable' => 'boolean',
            'meta' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (IncidentMedia $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
            }
            if ($m->getAttribute('immutable') === null) {
                $m->setAttribute('immutable', true);
            }
            if ($m->getAttribute('immutable_note') === null || $m->getAttribute('immutable_note') === '') {
                $m->setAttribute(
                    'immutable_note',
                    'Evidence is immutable. Linked MediaVault assets must not be altered; custody events record access.'
                );
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /** @return BelongsTo<Incident, $this> */
    public function incident(): BelongsTo
    {
        return $this->belongsTo(Incident::class);
    }

    /** @return BelongsTo<MediaAsset, $this> */
    public function mediaAsset(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class);
    }
}
