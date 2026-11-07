<?php

declare(strict_types=1);

namespace App\Core\Tenancy;

use App\Core\Modules\Models\Module;
use App\Core\Modules\Models\TenantModule;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property array<string, mixed>|null $branding
 * @property array<string, mixed>|null $data_retention
 */
class Tenant extends Model
{
    use SoftDeletes;

    protected $table = 'tenants';

    protected $fillable = [
        'ulid',
        'name',
        'slug',
        'legal_name',
        'country_code',
        'locale',
        'timezone',
        'currency',
        'week_starts_on',
        'branding',
        'custom_domain',
        'labour_preset',
        'ai_budget_cents',
        'storage_quota_mb',
        'is_demo',
        'is_active',
        'data_retention',
    ];

    protected function casts(): array
    {
        return [
            'branding' => 'array',
            'data_retention' => 'array',
            'is_demo' => 'boolean',
            'is_active' => 'boolean',
            'ai_budget_cents' => 'integer',
            'storage_quota_mb' => 'integer',
            'week_starts_on' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Tenant $tenant): void {
            if ($tenant->getAttribute('ulid') === null || $tenant->getAttribute('ulid') === '') {
                $tenant->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /**
     * @return HasMany<User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * @return HasMany<TenantModule, $this>
     */
    public function tenantModules(): HasMany
    {
        return $this->hasMany(TenantModule::class);
    }

    /**
     * @return BelongsToMany<Module, $this>
     */
    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'tenant_modules')
            ->withPivot(['enabled', 'enabled_at', 'source'])
            ->withTimestamps();
    }
}
