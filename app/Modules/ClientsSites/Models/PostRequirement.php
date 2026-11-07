<?php

declare(strict_types=1);

namespace App\Modules\ClientsSites\Models;

use App\Core\Tenancy\TenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PostRequirement extends TenantModel
{
    protected $fillable = [
        'ulid',
        'tenant_id',
        'post_id',
        'role_label',
        'guards_required',
        'hours_label',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'guards_required' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (PostRequirement $requirement): void {
            if ($requirement->getAttribute('ulid') === null || $requirement->getAttribute('ulid') === '') {
                $requirement->setAttribute('ulid', (string) Str::ulid());
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }

    /**
     * @return BelongsTo<Post, $this>
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
