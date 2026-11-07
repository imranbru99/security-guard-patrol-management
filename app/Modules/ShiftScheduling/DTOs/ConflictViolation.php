<?php

declare(strict_types=1);

namespace App\Modules\ShiftScheduling\DTOs;

final class ConflictViolation
{
    public const SEVERITY_HARD = 'hard';

    public const SEVERITY_SOFT = 'soft';

    public const CODE_OVERLAP = 'overlap';

    public const CODE_REST_PERIOD = 'rest_period';

    /**
     * @param  array<string, mixed>  $meta
     */
    public function __construct(
        public readonly string $code,
        public readonly string $severity,
        public readonly int $guardId,
        public readonly ?int $assignmentIdA = null,
        public readonly ?int $assignmentIdB = null,
        public readonly string $message = '',
        public readonly array $meta = [],
    ) {}

    /**
     * @return array{
     *     code: string,
     *     severity: string,
     *     guard_id: int,
     *     assignment_id_a: int|null,
     *     assignment_id_b: int|null,
     *     message: string,
     *     meta: array<string, mixed>
     * }
     */
    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'severity' => $this->severity,
            'guard_id' => $this->guardId,
            'assignment_id_a' => $this->assignmentIdA,
            'assignment_id_b' => $this->assignmentIdB,
            'message' => $this->message,
            'meta' => $this->meta,
        ];
    }

    public function isHard(): bool
    {
        return $this->severity === self::SEVERITY_HARD;
    }
}
