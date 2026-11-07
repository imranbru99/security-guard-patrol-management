<?php

declare(strict_types=1);

namespace App\Modules\AttendanceTracking\Http\Controllers;

use App\Core\Support\Geo;
use App\Modules\AttendanceTracking\Models\AttendanceException;
use App\Modules\AttendanceTracking\Models\AttendanceSummary;
use App\Modules\ClientsSites\Models\Site;
use App\Modules\GuardsEmployees\Models\Guard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use ImranDevBd\AttendanceHub\DTOs\AttendancePunch;
use ImranDevBd\AttendanceHub\Enums\PunchType;
use ImranDevBd\AttendanceHub\Enums\VerifyMode;
use ImranDevBd\AttendanceHub\Facades\AttendanceHub;
use ImranDevBd\AttendanceHub\Support\AttendanceNormalizer;

final class AttendanceController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'attendance',
            'status' => 'enabled',
        ]);
    }

    public function index(): JsonResponse
    {
        $rows = AttendanceSummary::query()->orderByDesc('work_date')->limit(100)->get([
            'ulid', 'guard_id', 'work_date', 'status', 'worked_minutes', 'late_minutes', 'overtime_minutes',
        ]);

        return response()->json(['data' => $rows]);
    }

    public function virtualCheckIn(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'string'],
            'lat' => ['required', 'numeric'],
            'lng' => ['required', 'numeric'],
            'site_ulid' => ['nullable', 'string'],
            'radius_meters' => ['nullable', 'integer', 'min:1'],
            'method' => ['nullable', 'string'],
            'punch_type' => ['nullable', 'string'],
            'qr_token' => ['nullable', 'string'],
            'photo_url' => ['nullable', 'string'],
            'metadata' => ['nullable', 'array'],
        ]);

        $lat = (float) $validated['lat'];
        $lng = (float) $validated['lng'];
        $radius = (int) ($validated['radius_meters'] ?? 100);

        $site = null;
        if (! empty($validated['site_ulid'])) {
            $site = Site::query()->where('ulid', $validated['site_ulid'])->first();
        }

        $geofence = $this->validateGeofence($lat, $lng, $radius, $site);
        if ($geofence['ok'] === false) {
            $guard = Guard::query()->where('employee_id', $validated['employee_id'])->first();
            if ($guard !== null) {
                AttendanceException::query()->create([
                    'tenant_id' => $guard->tenant_id,
                    'guard_id' => $guard->id,
                    'work_date' => now()->toDateString(),
                    'type' => 'out_of_geofence',
                    'status' => 'open',
                    'notes' => $geofence['message'],
                    'payload' => [
                        'lat' => $lat,
                        'lng' => $lng,
                        'site_ulid' => $validated['site_ulid'] ?? null,
                        'distance_meters' => $geofence['distance_meters'] ?? null,
                    ],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $geofence['message'],
                'distance_meters' => $geofence['distance_meters'] ?? null,
            ], 422);
        }

        $method = strtolower((string) ($validated['method'] ?? 'gps'));
        $verifyMode = match ($method) {
            'qr' => VerifyMode::QR,
            'selfie', 'face' => VerifyMode::FACE,
            default => VerifyMode::GPS,
        };

        $punchType = isset($validated['punch_type'])
            ? (PunchType::tryFrom($validated['punch_type']) ?? PunchType::AUTO)
            : PunchType::AUTO;

        $punch = new AttendancePunch(
            deviceUserId: $validated['employee_id'],
            punchedAt: now(),
            verifyMode: $verifyMode,
            punchType: $punchType,
            deviceId: null,
            employeeId: $validated['employee_id'],
            rawPayload: [
                'virtual_method' => $method,
                'photo_url' => $validated['photo_url'] ?? null,
                'qr_token' => $validated['qr_token'] ?? null,
                'metadata' => array_merge($validated['metadata'] ?? [], [
                    'site_ulid' => $validated['site_ulid'] ?? null,
                    'geofence' => $geofence,
                ]),
            ],
            location: [
                'lat' => $lat,
                'lng' => $lng,
                'distance_meters' => $geofence['distance_meters'] ?? null,
                'allowed_radius' => $radius,
            ],
        );

        if (class_exists(AttendanceHub::class) && method_exists(AttendanceHub::class, 'fake')) {
            // Keep hub fakeable for CI / demos without changing punch persistence path.
            try {
                if (! AttendanceHub::isFake()) {
                    // no-op: caller may have already faked
                }
            } catch (\Throwable) {
                // Facade root may be unbound in some contexts; ignore.
            }
        }

        $log = (new AttendanceNormalizer)->record($punch);

        return response()->json([
            'success' => true,
            'message' => 'Virtual check-in recorded.',
            'geofence' => $geofence,
            'data' => $log,
        ], 201);
    }

    /**
     * @return array{ok: bool, message: string, distance_meters?: float|null, method?: string}
     */
    private function validateGeofence(float $lat, float $lng, int $radius, ?Site $site): array
    {
        if ($site === null) {
            return [
                'ok' => true,
                'message' => 'No site geofence supplied; accepted.',
                'method' => 'none',
            ];
        }

        $polygon = $site->geofence_polygon;
        if (is_array($polygon) && count($polygon) >= 3) {
            if (! Geo::pointInPolygon($lat, $lng, $polygon)) {
                return [
                    'ok' => false,
                    'message' => 'Outside site geofence polygon.',
                    'method' => 'polygon',
                    'distance_meters' => null,
                ];
            }

            return [
                'ok' => true,
                'message' => 'Inside site geofence polygon.',
                'method' => 'polygon',
            ];
        }

        if ($site->latitude === null || $site->longitude === null) {
            return [
                'ok' => true,
                'message' => 'Site has no coordinates; accepted.',
                'method' => 'none',
            ];
        }

        $distance = Geo::haversineMeters(
            $lat,
            $lng,
            (float) $site->latitude,
            (float) $site->longitude,
        );

        if ($distance > $radius) {
            return [
                'ok' => false,
                'message' => sprintf('Outside allowed geofence (%.1fm > %dm).', $distance, $radius),
                'method' => 'haversine',
                'distance_meters' => round($distance, 2),
            ];
        }

        return [
            'ok' => true,
            'message' => 'Inside haversine radius.',
            'method' => 'haversine',
            'distance_meters' => round($distance, 2),
        ];
    }
}
