<?php

declare(strict_types=1);

namespace App\Modules\SmartScheduling\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ForecastRun extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'tenant_id',
        'horizon',
        'ran_at',
        'status',
        'inputs',
        'gaps',
        'summary',
    ];

    protected function casts(): array
    {
        return [
            'ran_at' => 'datetime',
            'inputs' => 'array',
            'gaps' => 'array',
            'summary' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ForecastRun $m): void {
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
