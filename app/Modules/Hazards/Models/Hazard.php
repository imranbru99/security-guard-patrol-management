<?php

declare(strict_types=1);

namespace App\Modules\Hazards\Models;

use App\Core\Tenancy\TenantModel;
use App\Models\User;
use App\Modules\ClientsSites\Models\Site;
use App\Modules\GuardsEmployees\Models\Guard;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Hazard extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'tenant_id',
        'site_id',
        'reported_by_guard_id',
        'assigned_to_user_id',
        'title',
        'description',
        'category',
        'severity',
        'status',
        'reported_at',
        'resolved_at',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'reported_at' => 'datetime',
            'resolved_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Hazard $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
            }
            if ($m->getAttribute('reported_at') === null) {
                $m->setAttribute('reported_at', now());
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
    public function reportedByGuardEmployee(): BelongsTo
    {
        return $this->belongsTo(Guard::class, 'reported_by_guard_id');
    }

    /** @return BelongsTo<User, $this> */
    public function assignedToUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }
}
