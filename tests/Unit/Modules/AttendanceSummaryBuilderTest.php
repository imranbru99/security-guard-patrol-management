<?php

declare(strict_types=1);

use App\Core\Tenancy\Tenant;
use App\Modules\AttendanceTracking\Services\AttendanceSummaryBuilder;
use App\Modules\GuardsEmployees\Models\Guard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ImranDevBd\AttendanceHub\DTOs\AttendanceShift;
use ImranDevBd\AttendanceHub\DTOs\DailyAttendanceSummary;
use ImranDevBd\AttendanceHub\Facades\AttendanceHub;
use ImranDevBd\AttendanceHub\Models\AttendanceLog;
use ImranDevBd\AttendanceHub\Support\AttendanceCalculator;

uses(RefreshDatabase::class);

it('builds a persisted summary using AttendanceCalculator when available', function (): void {
    if (method_exists(AttendanceHub::class, 'fake')) {
        AttendanceHub::fake();
    }

    $tenant = Tenant::query()->create([
        'name' => 'Summary Co',
        'slug' => 'summary-co',
        'locale' => 'en',
        'timezone' => 'UTC',
        'currency' => 'USD',
    ]);

    $guard = Guard::query()->create([
        'tenant_id' => $tenant->id,
        'employee_id' => 'EMP-42',
        'name' => 'Test Guard',
        'status' => 'active',
    ]);

    AttendanceLog::query()->create([
        'device_user_id' => 'EMP-42',
        'employee_id' => 'EMP-42',
        'punched_at' => '2026-11-08 09:05:00',
        'punch_type' => 'check_in',
        'punch_hash' => hash('sha256', 'emp-42-in'),
        'verify_mode' => 'gps',
    ]);
    AttendanceLog::query()->create([
        'device_user_id' => 'EMP-42',
        'employee_id' => 'EMP-42',
        'punched_at' => '2026-11-08 17:00:00',
        'punch_type' => 'check_out',
        'punch_hash' => hash('sha256', 'emp-42-out'),
        'verify_mode' => 'gps',
    ]);

    expect(class_exists(AttendanceCalculator::class))->toBeTrue();

    $builder = new AttendanceSummaryBuilder(new AttendanceCalculator(
        new AttendanceShift(name: 'Day', startTime: '09:00', endTime: '17:00', gracePeriodMinutes: 10)
    ));

    $summary = $builder->buildForGuard($guard, '2026-11-08');

    expect($summary->guard_id)->toBe($guard->id)
        ->and($summary->work_date->toDateString())->toBe('2026-11-08')
        ->and($summary->punches_count)->toBeGreaterThan(0)
        ->and(in_array($summary->status, [
            DailyAttendanceSummary::STATUS_PRESENT,
            DailyAttendanceSummary::STATUS_LATE,
            DailyAttendanceSummary::STATUS_HALF_DAY,
            DailyAttendanceSummary::STATUS_ABSENT,
        ], true))->toBeTrue();
});
