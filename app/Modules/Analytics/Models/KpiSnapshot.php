<?php

declare(strict_types=1);

namespace App\Modules\Analytics\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class KpiSnapshot extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid', 'tenant_id', 'period', 'snapshot_on', 'metrics',
    ];

    protected function casts(): array
    {
        return [
            'snapshot_on' => 'date',
            'metrics' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (KpiSnapshot $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }
}
