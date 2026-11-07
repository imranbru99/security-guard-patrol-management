<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CatalogItem extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'tenant_id',
        'catalog_category_id',
        'name',
        'slug',
        'sku',
        'item_type',
        'summary',
        'body',
        'price_cents',
        'currency',
        'meta',
        'ai_status',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'price_cents' => 'integer',
            'meta' => 'array',
            'is_published' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (CatalogItem $m): void {
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

    /** @return BelongsTo<CatalogCategory, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(CatalogCategory::class, 'catalog_category_id');
    }
}
