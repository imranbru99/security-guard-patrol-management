<?php

declare(strict_types=1);

namespace App\Modules\AttendanceTracking\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\GuardsEmployees\Models\Guard;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class AttendanceException extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'tenant_id',
        'guard_id',
        'work_date',
        'type',
        'status',
        'notes',
        'payload',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'work_date' => 'date',
            'payload' => 'array',
            'resolved_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (AttendanceException $m): void {
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
}
