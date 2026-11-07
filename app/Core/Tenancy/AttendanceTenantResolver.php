<?php

declare(strict_types=1);

namespace App\Core\Tenancy;

final class AttendanceTenantResolver
{
    public function __invoke(): ?int
    {
        return TenantContext::id() ?? auth()->user()?->tenant_id;
    }
}
