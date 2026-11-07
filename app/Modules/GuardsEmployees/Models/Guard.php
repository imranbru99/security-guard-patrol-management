<?php

declare(strict_types=1);

namespace App\Modules\GuardsEmployees\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\TenancyOrganisation\Models\Branch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Guard extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'tenant_id',
        'employee_id',
        'name',
        'status',
        'branch_id',
        'photo_path',
        'badge_qr',
        'skills',
        'emergency_contact',
    ];

    protected function casts(): array
    {
        return [
            'skills' => 'array',
            'emergency_contact' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Guard $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /**
     * @return BelongsTo<Branch, $this>
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
