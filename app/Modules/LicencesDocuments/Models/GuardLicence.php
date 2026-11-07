<?php

declare(strict_types=1);

namespace App\Modules\LicencesDocuments\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\GuardsEmployees\Models\Guard;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class GuardLicence extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'tenant_id',
        'guard_id',
        'licence_type_id',
        'number',
        'expires_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (GuardLicence $m): void {
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
     * @return BelongsTo<Guard, $this>
     */
    public function guardEmployee(): BelongsTo
    {
        return $this->belongsTo(Guard::class);
    }

    /**
     * @return BelongsTo<LicenceType, $this>
     */
    public function licenceType(): BelongsTo
    {
        return $this->belongsTo(LicenceType::class);
    }
}
