<?php

declare(strict_types=1);

namespace App\Modules\LicencesDocuments\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class LicenceType extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'tenant_id',
        'name',
        'code',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (LicenceType $m): void {
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
     * @return HasMany<GuardLicence, $this>
     */
    public function guardLicences(): HasMany
    {
        return $this->hasMany(GuardLicence::class);
    }
}
