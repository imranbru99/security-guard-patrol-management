<?php

declare(strict_types=1);

namespace App\Modules\PatrolExecution\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\CheckpointsTags\Models\Checkpoint;
use App\Modules\GuardsEmployees\Models\Guard;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PatrolScan extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'patrol_tour_id',
        'checkpoint_id',
        'guard_id',
        'client_uuid',
        'scanned_at',
        'latitude',
        'longitude',
        'verification_type',
        'mock_location',
        'device_id',
        'notes',
        'photo_path',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'scanned_at' => 'datetime',
            'latitude' => 'float',
            'longitude' => 'float',
            'mock_location' => 'boolean',
            'meta' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (PatrolScan $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /** @return BelongsTo<PatrolTour, $this> */
    public function patrolTour(): BelongsTo
    {
        return $this->belongsTo(PatrolTour::class);
    }

    /** @return BelongsTo<Checkpoint, $this> */
    public function checkpoint(): BelongsTo
    {
        return $this->belongsTo(Checkpoint::class);
    }

    /** @return BelongsTo<Guard, $this> */
    public function guardEmployee(): BelongsTo
    {
        return $this->belongsTo(Guard::class, 'guard_id');
    }
}
