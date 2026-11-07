<?php

declare(strict_types=1);

namespace App\Modules\ShiftScheduling\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Rotation extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'tenant_id',
        'name',
        'pattern',
        'cycle_days',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'pattern' => 'array',
            'cycle_days' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Rotation $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /** @return HasMany<Roster, $this> */
    public function rosters(): HasMany
    {
        return $this->hasMany(Roster::class);
    }
}
