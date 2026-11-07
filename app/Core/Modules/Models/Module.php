<?php

declare(strict_types=1);

namespace App\Core\Modules\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    protected $fillable = [
        'key',
        'name',
        'group',
        'icon',
        'description',
        'features',
        'depends_on',
        'conflicts_with',
        'permissions',
        'plan_tier',
        'price_cents',
        'is_core',
        'manifest',
        'public_page',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'depends_on' => 'array',
            'conflicts_with' => 'array',
            'permissions' => 'array',
            'manifest' => 'array',
            'public_page' => 'array',
            'is_core' => 'boolean',
            'price_cents' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    /**
     * @return HasMany<TenantModule, $this>
     */
    public function tenantModules(): HasMany
    {
        return $this->hasMany(TenantModule::class);
    }

    /**
     * @return list<string>
     */
    public function dependencyKeys(): array
    {
        /** @var list<string> $deps */
        $deps = array_values(array_filter(
            array_map('strval', $this->depends_on ?? [])
        ));

        return $deps;
    }

    /**
     * @return list<string>
     */
    public function conflictKeys(): array
    {
        /** @var list<string> $conflicts */
        $conflicts = array_values(array_filter(
            array_map('strval', $this->conflicts_with ?? [])
        ));

        return $conflicts;
    }
}
