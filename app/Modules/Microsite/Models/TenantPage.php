<?php

declare(strict_types=1);

namespace App\Modules\Microsite\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TenantPage extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid', 'tenant_id', 'title', 'slug', 'page_type', 'body', 'seo', 'is_published', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'seo' => 'array',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (TenantPage $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
            }
            if (($m->getAttribute('slug') === null || $m->getAttribute('slug') === '') && is_string($m->getAttribute('title'))) {
                $m->setAttribute('slug', Str::slug((string) $m->getAttribute('title')));
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }
}
