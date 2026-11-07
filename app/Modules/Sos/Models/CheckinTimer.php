<?php

declare(strict_types=1);

namespace App\Modules\Sos\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\GuardsEmployees\Models\Guard;
use App\Modules\ShiftScheduling\Models\Shift;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CheckinTimer extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'tenant_id',
        'guard_id',
        'shift_id',
        'interval_minutes',
        'next_due_at',
        'last_checkin_at',
        'is_active',
        'status',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'interval_minutes' => 'integer',
            'next_due_at' => 'datetime',
            'last_checkin_at' => 'datetime',
            'is_active' => 'boolean',
            'meta' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (CheckinTimer $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /** @return BelongsTo<Guard, $this> */
    public function guardEmployee(): BelongsTo
    {
        return $this->belongsTo(Guard::class, 'guard_id');
    }

    /** @return BelongsTo<Shift, $this> */
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }
}
