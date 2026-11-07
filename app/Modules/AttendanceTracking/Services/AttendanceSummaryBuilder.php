<?php

declare(strict_types=1);

namespace App\Modules\AttendanceTracking\Services;

use App\Modules\AttendanceTracking\Models\AttendanceSummary;
use App\Modules\GuardsEmployees\Models\Guard;
use App\Modules\ShiftScheduling\Models\Shift;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use ImranDevBd\AttendanceHub\DTOs\AttendanceShift;
use ImranDevBd\AttendanceHub\DTOs\DailyAttendanceSummary;
use ImranDevBd\AttendanceHub\Facades\AttendanceHub;
use ImranDevBd\AttendanceHub\Support\AttendanceCalculator;
use RuntimeException;

/**
 * Builds and persists tenant attendance summaries using AttendanceHub's calculator when available.
 */
final class AttendanceSummaryBuilder
{
    public function __construct(
        private readonly ?AttendanceCalculator $calculator = null,
    ) {}

    public function buildForGuard(
        Guard $guard,
        CarbonInterface|string $date,
        ?Shift $shift = null,
    ): AttendanceSummary {
        $date = is_string($date) ? Carbon::parse($date) : Carbon::instance($date);
        $dateStr = $date->toDateString();

        $hubShift = $this->toAttendanceShift($shift);
        $summary = $this->calculate($guard->employee_id, $date, $hubShift);

        return AttendanceSummary::query()->updateOrCreate(
            [
                'tenant_id' => $guard->tenant_id,
                'guard_id' => $guard->id,
                'work_date' => $dateStr,
            ],
            [
                'status' => $summary->status,
                'first_in' => $summary->firstIn,
                'last_out' => $summary->lastOut,
                'worked_minutes' => $summary->totalWorkedMinutes,
                'late_minutes' => $summary->lateMinutes,
                'early_leave_minutes' => $summary->earlyLeaveMinutes,
                'overtime_minutes' => $summary->overtimeMinutes,
                'punches_count' => $summary->punchesCount,
                'shift_name' => $summary->shiftName,
                'shift_id' => $shift?->id,
                'source' => 'hub',
                'meta' => [
                    'employee_id' => $summary->employeeId,
                    'raw_punches_count' => count($summary->rawPunches),
                ],
            ]
        );
    }

    public function calculate(
        string $employeeId,
        CarbonInterface|string $date,
        ?AttendanceShift $shift = null,
    ): DailyAttendanceSummary {
        $calculator = $this->resolveCalculator($shift);

        return $calculator->calculate($employeeId, $date, $shift);
    }

    private function resolveCalculator(?AttendanceShift $shift): AttendanceCalculator
    {
        if ($this->calculator !== null) {
            return $this->calculator;
        }

        if (! class_exists(AttendanceCalculator::class)) {
            throw new RuntimeException('ImranDevBd\\AttendanceHub\\Support\\AttendanceCalculator is not available.');
        }

        if (class_exists(AttendanceHub::class) && method_exists(AttendanceHub::class, 'calculator')) {
            return AttendanceHub::calculator($shift);
        }

        return new AttendanceCalculator($shift);
    }

    private function toAttendanceShift(?Shift $shift): ?AttendanceShift
    {
        if ($shift === null) {
            return null;
        }

        return new AttendanceShift(
            name: $shift->label ?: 'Assigned Shift',
            startTime: $shift->starts_at?->format('H:i') ?? '09:00',
            endTime: $shift->ends_at?->format('H:i') ?? '18:00',
        );
    }
}
