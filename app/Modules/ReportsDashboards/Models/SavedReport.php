<?php

declare(strict_types=1);

namespace App\Modules\ReportsDashboards\Models;

use App\Core\Tenancy\TenantModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class SavedReport extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'name',
        'type',
        'filters',
        'schedule',
        'created_by',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'filters' => 'array',
            'schedule' => 'array',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (SavedReport $report): void {
            if (empty($report->getAttribute('ulid'))) {
                $report->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
