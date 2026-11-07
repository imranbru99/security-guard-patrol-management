<?php

declare(strict_types=1);

namespace App\Modules\SettingsForms\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Support\Str;

class CustomField extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'entity_type',
        'key',
        'label',
        'type',
        'options',
        'required',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'required' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (CustomField $field): void {
            if (empty($field->getAttribute('ulid'))) {
                $field->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }
}
