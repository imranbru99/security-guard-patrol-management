<?php

declare(strict_types=1);

namespace App\Modules\SettingsForms\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class FormTemplate extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'name',
        'slug',
        'description',
        'schema',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'schema' => 'array',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (FormTemplate $template): void {
            if (empty($template->getAttribute('ulid'))) {
                $template->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /**
     * @return HasMany<FormSubmission, $this>
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class);
    }
}
