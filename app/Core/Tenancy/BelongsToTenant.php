<?php

declare(strict_types=1);

namespace App\Core\Tenancy;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin Model
 */
trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::creating(function (Model $model): void {
            if ($model->getAttribute('tenant_id') === null && TenantContext::check()) {
                $model->setAttribute('tenant_id', TenantContext::id());
            }
        });

        static::addGlobalScope('tenant', function (Builder $builder): void {
            if (TenantContext::check()) {
                $builder->where(
                    $builder->getModel()->getTable().'.tenant_id',
                    TenantContext::id()
                );
            }
        });
    }

    /**
     * @return BelongsTo<Tenant, Model>
     */
    public function tenant(): BelongsTo
    {
        /** @var BelongsTo<Tenant, Model> $relation */
        $relation = $this->belongsTo(Tenant::class);

        return $relation;
    }
}
