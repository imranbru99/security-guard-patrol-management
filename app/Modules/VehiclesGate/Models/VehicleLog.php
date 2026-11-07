<?php

declare(strict_types=1);

namespace App\Modules\VehiclesGate\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\ClientsSites\Models\Site;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class VehicleLog extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'tenant_id',
        'site_id',
        'parking_slot_id',
        'plate_number',
        'vehicle_type',
        'driver_name',
        'driver_id_number',
        'purpose',
        'direction',
        'weighbridge_in_kg',
        'weighbridge_out_kg',
        'entered_at',
        'exited_at',
        'violation',
        'violation_note',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'weighbridge_in_kg' => 'decimal:2',
            'weighbridge_out_kg' => 'decimal:2',
            'entered_at' => 'datetime',
            'exited_at' => 'datetime',
            'violation' => 'boolean',
            'meta' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (VehicleLog $m): void {
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

    /** @return BelongsTo<ParkingSlot, $this> */
    public function parkingSlot(): BelongsTo
    {
        return $this->belongsTo(ParkingSlot::class);
    }

    /** @return HasMany<GatePass, $this> */
    public function gatePasses(): HasMany
    {
        return $this->hasMany(GatePass::class);
    }
}
