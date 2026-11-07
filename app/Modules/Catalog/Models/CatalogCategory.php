<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CatalogCategory extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'tenant_id',
        'name',
        'slug',
        'kind',
        'description',
        'sort_order',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (CatalogCategory $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
            }
            if (($m->getAttribute('slug') === null || $m->getAttribute('slug') === '') && is_string($m->getAttribute('name'))) {
                $m->setAttribute('slug', Str::slug((string) $m->getAttribute('name')));
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /** @return HasMany<CatalogItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(CatalogItem::class);
    }
}
