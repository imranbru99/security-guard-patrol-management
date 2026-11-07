<?php

declare(strict_types=1);

namespace App\Providers;

use App\Core\Tenancy\AttendanceTenantResolver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Model::preventLazyLoading(! $this->app->isProduction());
        Model::shouldBeStrict(! $this->app->isProduction() && ! $this->app->runningUnitTests());

        config([
            'attendance-hub.tenancy.tenant_resolver' => app(AttendanceTenantResolver::class),
        ]);
    }
}
