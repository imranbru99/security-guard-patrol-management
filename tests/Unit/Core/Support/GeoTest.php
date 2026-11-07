<?php

declare(strict_types=1);

use App\Core\Support\Geo;

it('computes haversine distance for known short baseline', function (): void {
    // ~111.2 km per degree latitude near equator; 0.001° ≈ 111.2 m
    $meters = Geo::haversineMeters(0.0, 0.0, 0.001, 0.0);

    expect($meters)->toBeGreaterThan(110.0)
        ->and($meters)->toBeLessThan(112.5);
});

it('returns zero for identical points', function (): void {
    expect(Geo::haversineMeters(23.81, 90.41, 23.81, 90.41))->toBe(0.0);
});

it('detects point inside a square polygon', function (): void {
    $square = [
        ['lat' => 0.0, 'lng' => 0.0],
        ['lat' => 0.0, 'lng' => 1.0],
        ['lat' => 1.0, 'lng' => 1.0],
        ['lat' => 1.0, 'lng' => 0.0],
    ];

    expect(Geo::pointInPolygon(0.5, 0.5, $square))->toBeTrue()
        ->and(Geo::pointInPolygon(1.5, 0.5, $square))->toBeFalse();
});

it('accepts indexed lng/lat pairs for polygons', function (): void {
    // GeoJSON order: [lng, lat]
    $triangle = [
        [0.0, 0.0],
        [0.0, 2.0],
        [2.0, 0.0],
    ];

    expect(Geo::pointInPolygon(0.4, 0.4, $triangle))->toBeTrue()
        ->and(Geo::pointInPolygon(1.5, 1.5, $triangle))->toBeFalse();
});

it('aliases distanceMeters to haversineMeters', function (): void {
    $a = Geo::haversineMeters(10.0, 20.0, 10.1, 20.1);
    $b = Geo::distanceMeters(10.0, 20.0, 10.1, 20.1);

    expect($a)->toEqual($b)
        ->and(Geo::withinRadius(0.0, 0.0, 0.0, 0.0, 10.0))->toBeTrue();
});
