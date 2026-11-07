<?php

declare(strict_types=1);

namespace App\Modules\VehiclesGate\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\ClientsSites\Models\Site;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class GatePass extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'tenant_id',
        'site_id',
        'vehicle_log_id',
        'pass_number',
        'goods_description',
        'requester_name',
        'status',
        'approved_by',
        'approved_at',
        'valid_from',
        'valid_to',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'valid_from' => 'datetime',
            'valid_to' => 'datetime',
            'meta' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (GatePass $m): void {
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

    /** @return BelongsTo<VehicleLog, $this> */
    public function vehicleLog(): BelongsTo
    {
        return $this->belongsTo(VehicleLog::class);
    }
}
