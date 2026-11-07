<?php

declare(strict_types=1);

namespace App\Modules\KeyControl\Models;

use App\Core\Tenancy\TenantModel;
use App\Modules\GuardsEmployees\Models\Guard;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class KeyLog extends TenantModel
{
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'tenant_id',
        'key_item_id',
        'issued_to_guard_id',
        'issued_to_name',
        'issued_at',
        'due_at',
        'returned_at',
        'signature_path',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
            'due_at' => 'datetime',
            'returned_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (KeyLog $m): void {
            if ($m->getAttribute('ulid') === null || $m->getAttribute('ulid') === '') {
                $m->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    public function isOverdue(?CarbonInterface $now = null): bool
    {
        if ($this->returned_at !== null) {
            return false;
        }

        if ($this->status === 'returned' || $this->status === 'lost') {
            return false;
        }

        if ($this->due_at === null) {
            return false;
        }

        $now ??= now();

        return $this->due_at->lt($now);
    }

    /** @return BelongsTo<KeyItem, $this> */
    public function keyItem(): BelongsTo
    {
        return $this->belongsTo(KeyItem::class);
    }

    /** @return BelongsTo<Guard, $this> */
    public function issuedToGuardEmployee(): BelongsTo
    {
        return $this->belongsTo(Guard::class, 'issued_to_guard_id');
    }
}
