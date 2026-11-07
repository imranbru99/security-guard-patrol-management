<?php

declare(strict_types=1);

namespace App\Core\Tenancy;

use RuntimeException;

final class TenantContext
{
    private static ?Tenant $tenant = null;

    public static function set(?Tenant $tenant): void
    {
        self::$tenant = $tenant;
    }

    public static function clear(): void
    {
        self::$tenant = null;
    }

    public static function get(): ?Tenant
    {
        return self::$tenant;
    }

    public static function id(): ?int
    {
        return self::$tenant?->id;
    }

    public static function require(): Tenant
    {
        if (self::$tenant === null) {
            throw new RuntimeException('No tenant is set in TenantContext.');
        }

        return self::$tenant;
    }

    public static function check(): bool
    {
        return self::$tenant !== null;
    }
}
