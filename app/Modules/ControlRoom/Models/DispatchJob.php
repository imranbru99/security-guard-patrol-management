<?php

declare(strict_types=1);

namespace App\Modules\ControlRoom\Models;

use App\Core\Tenancy\TenantModel;
use App\Models\User;
use App\Modules\ClientsSites\Models\Site;
use App\Modules\GuardsEmployees\Models\Guard;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class DispatchJob extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'tenant_id',
        'site_id',
        'assigned_guard_id',
        'created_by',
        'alert_type',
        'source_type',
        'source_id',
        'priority',
        'status',
        'summary',
        'acknowledged_at',
        'dispatched_at',
        'closed_at',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'acknowledged_at' => 'datetime',
            'dispatched_at' => 'datetime',
            'closed_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (DispatchJob $m): void {
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

    /** @return BelongsTo<Guard, $this> */
    public function assignedGuardEmployee(): BelongsTo
    {
        return $this->belongsTo(Guard::class, 'assigned_guard_id');
    }

    /** @return BelongsTo<User, $this> */
    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** @return HasMany<DispatchLog, $this> */
    public function logs(): HasMany
    {
        return $this->hasMany(DispatchLog::class);
    }
}
