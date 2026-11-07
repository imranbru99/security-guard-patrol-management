<?php

declare(strict_types=1);

namespace App\Core\Concerns;

use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\TenantContext;

trait HasModuleGate
{
    abstract public static function moduleId(): string;

    public static function canAccess(): bool
    {
        $tenant = TenantContext::get();
        if ($tenant === null) {
            return false;
        }

        return app(ModuleManager::class)->enabled($tenant, static::moduleId());
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }
}
