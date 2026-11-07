<?php

declare(strict_types=1);

namespace App\Modules\DisciplinePerformance\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\GuardsEmployees\Models\Guard;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class DisciplinaryRecord extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'tenant_id',
        'guard_id',
        'severity',
        'title',
        'description',
        'occurred_on',
        'outcome',
    ];

    protected function casts(): array
    {
        return [
            'occurred_on' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (DisciplinaryRecord $m): void {
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
        return $this->belongsTo(Guard::class);
    }
}
