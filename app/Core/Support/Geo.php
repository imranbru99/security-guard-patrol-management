<?php

declare(strict_types=1);

namespace App\Core\Support;

/**
 * Shared geospatial helpers (haversine distance, radius checks, polygon contains).
 */
final class Geo
{
    private const EARTH_RADIUS_METERS = 6371000.0;

    /**
     * Great-circle distance between two WGS84 points in metres.
     */
    public static function haversineMeters(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2,
    ): float {
        $φ1 = deg2rad($lat1);
        $φ2 = deg2rad($lat2);
        $Δφ = deg2rad($lat2 - $lat1);
        $Δλ = deg2rad($lon2 - $lon1);

        $a = sin($Δφ / 2) ** 2
            + cos($φ1) * cos($φ2) * sin($Δλ / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return self::EARTH_RADIUS_METERS * $c;
    }

    /**
     * Alias used by patrol / live-tracking modules.
     */
    public static function distanceMeters(
        float $lat1,
        float $lng1,
        float $lat2,
        float $lng2,
    ): float {
        return self::haversineMeters($lat1, $lng1, $lat2, $lng2);
    }

    public static function withinRadius(
        float $lat1,
        float $lng1,
        float $lat2,
        float $lng2,
        float $radiusMeters,
    ): bool {
        if ($radiusMeters <= 0) {
            return true;
        }

        return self::haversineMeters($lat1, $lng1, $lat2, $lng2) <= $radiusMeters;
    }

    /**
     * Ray-casting point-in-polygon.
     *
     * Indexed pairs are treated as [lng, lat] (GeoJSON order). Associative
     * keys lat/lng or latitude/longitude are also accepted.
     *
     * @param  list<array{0?: float, 1?: float, lat?: float, lng?: float, longitude?: float, latitude?: float}>  $polygon
     */
    public static function pointInPolygon(float $lat, float $lng, array $polygon): bool
    {
        $points = [];
        foreach ($polygon as $point) {
            if (isset($point['lat'], $point['lng'])) {
                $points[] = [(float) $point['lng'], (float) $point['lat']];
            } elseif (isset($point['latitude'], $point['longitude'])) {
                $points[] = [(float) $point['longitude'], (float) $point['latitude']];
            } elseif (isset($point[0], $point[1])) {
                $points[] = [(float) $point[0], (float) $point[1]];
            }
        }

        $n = count($points);
        if ($n < 3) {
            return false;
        }

        $inside = false;
        for ($i = 0, $j = $n - 1; $i < $n; $j = $i++) {
            $xi = $points[$i][0];
            $yi = $points[$i][1];
            $xj = $points[$j][0];
            $yj = $points[$j][1];

            $intersect = (($yi > $lat) !== ($yj > $lat))
                && ($lng < ($xj - $xi) * ($lat - $yi) / (($yj - $yi) ?: 1e-12) + $xi);

            if ($intersect) {
                $inside = ! $inside;
            }
        }

        return $inside;
    }
}
