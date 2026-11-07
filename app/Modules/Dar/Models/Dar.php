<?php

declare(strict_types=1);

namespace App\Modules\Dar\Models;

use App\Core\Tenancy\TenantModel;
use App\Models\User;
use App\Modules\ClientsSites\Models\Site;
use App\Modules\GuardsEmployees\Models\Guard;
use App\Modules\ShiftScheduling\Models\Shift;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Dar extends TenantModel
{
    use SoftDeletes;

    protected $table = 'dars';

    protected $fillable = [
        'ulid',
        'tenant_id',
        'shift_id',
        'site_id',
        'guard_id',
        'report_date',
        'status',
        'client_visible',
        'signed_off_at',
        'signed_off_by',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'report_date' => 'date',
            'client_visible' => 'boolean',
            'signed_off_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Dar $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /** @return BelongsTo<Shift, $this> */
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    /** @return BelongsTo<Site, $this> */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /** @return BelongsTo<Guard, $this> */
    public function guardEmployee(): BelongsTo
    {
        return $this->belongsTo(Guard::class, 'guard_id');
    }

    /** @return BelongsTo<User, $this> */
    public function signedOffByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signed_off_by');
    }

    /** @return HasMany<DarEntry, $this> */
    public function entries(): HasMany
    {
        return $this->hasMany(DarEntry::class);
    }
}
