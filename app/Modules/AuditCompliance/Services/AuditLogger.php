<?php

declare(strict_types=1);

namespace App\Modules\AuditCompliance\Services;

use App\Core\Tenancy\Tenant;
use App\Modules\AuditCompliance\Models\AuditEvent;
use Illuminate\Support\Facades\DB;

final class AuditLogger
{
    /**
     * @param  array<string, mixed>|null  $before
     * @param  array<string, mixed>|null  $after
     */
    public function append(
        Tenant $tenant,
        string $action,
        ?string $subjectType = null,
        ?int $subjectId = null,
        ?array $before = null,
        ?array $after = null,
        ?int $actorId = null,
        ?string $ip = null,
    ): AuditEvent {
        return DB::transaction(function () use ($tenant, $action, $subjectType, $subjectId, $before, $after, $actorId, $ip): AuditEvent {
            /** @var AuditEvent|null $previous */
            $previous = AuditEvent::query()
                ->withoutGlobalScopes()
                ->where('tenant_id', $tenant->id)
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

            $prevHash = $previous === null
                ? str_repeat('0', 64)
                : (string) $previous->getAttribute('hash');

            $payload = [
                'tenant_id' => $tenant->id,
                'actor_id' => $actorId,
                'action' => $action,
                'subject_type' => $subjectType,
                'subject_id' => $subjectId,
                'before' => $before,
                'after' => $after,
                'ip' => $ip,
                'prev_hash' => $prevHash,
            ];

            $hash = $this->computeHash($payload);

            return AuditEvent::query()->create([
                ...$payload,
                'tenant_id' => $tenant->id,
                'hash' => $hash,
            ]);
        });
    }

    /**
     * @return array{valid: bool, checked: int, broken_at: string|null}
     */
    public function verifyChain(Tenant $tenant): array
    {
        $events = AuditEvent::query()
            ->withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->orderBy('id')
            ->get();

        $expectedPrev = str_repeat('0', 64);
        $checked = 0;

        foreach ($events as $event) {
            $checked++;

            if ($event->prev_hash !== $expectedPrev) {
                return ['valid' => false, 'checked' => $checked, 'broken_at' => $event->ulid];
            }

            $recomputed = $this->computeHash([
                'tenant_id' => $event->tenant_id,
                'actor_id' => $event->actor_id,
                'action' => $event->action,
                'subject_type' => $event->subject_type,
                'subject_id' => $event->subject_id,
                'before' => $event->before,
                'after' => $event->after,
                'ip' => $event->ip,
                'prev_hash' => $event->prev_hash,
            ]);

            if (! hash_equals($event->hash, $recomputed)) {
                return ['valid' => false, 'checked' => $checked, 'broken_at' => $event->ulid];
            }

            $expectedPrev = $event->hash;
        }

        return ['valid' => true, 'checked' => $checked, 'broken_at' => null];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function computeHash(array $payload): string
    {
        $canonical = json_encode([
            'tenant_id' => $payload['tenant_id'],
            'actor_id' => $payload['actor_id'] ?? null,
            'action' => $payload['action'],
            'subject_type' => $payload['subject_type'] ?? null,
            'subject_id' => $payload['subject_id'] ?? null,
            'before' => $payload['before'] ?? null,
            'after' => $payload['after'] ?? null,
            'ip' => $payload['ip'] ?? null,
            'prev_hash' => $payload['prev_hash'],
        ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);

        return hash('sha256', $canonical);
    }
}
