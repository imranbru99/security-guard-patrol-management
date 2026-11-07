<?php

declare(strict_types=1);

namespace App\Core\Providers;

use App\Core\Http\Middleware\EnsureModuleEnabled;
use App\Core\Modules\ManifestRegistry;
use App\Core\Modules\ModuleManager;
use App\Core\Modules\SyncModulesCommand;
use App\Core\Tenancy\TenantContext;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

final class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ManifestRegistry::class);
        $this->app->singleton(ModuleManager::class);
    }

    public function boot(Router $router): void
    {
        $router->aliasMiddleware('module', EnsureModuleEnabled::class);

        Blade::if('module', function (string $moduleKey): bool {
            $tenant = TenantContext::get();
            if ($tenant === null) {
                return false;
            }

            return app(ModuleManager::class)->enabled($tenant, $moduleKey);
        });

        if ($this->app->runningInConsole()) {
            $this->commands([SyncModulesCommand::class]);
        }

        $this->bootModulePackages();
    }

    private function bootModulePackages(): void
    {
        $path = app_path('Modules');
        if (! File::isDirectory($path)) {
            return;
        }

        foreach (File::directories($path) as $dir) {
            $migrations = $dir.'/Database/migrations';
            if (File::isDirectory($migrations)) {
                $this->loadMigrationsFrom($migrations);
            }

            $routes = $dir.'/routes.php';
            if (File::exists($routes)) {
                $this->loadRoutesFrom($routes);
            }

            $views = $dir.'/Views';
            if (File::isDirectory($views)) {
                $moduleKey = basename($dir);
                $this->loadViewsFrom($views, $moduleKey);
            }

            $lang = $dir.'/Lang';
            if (File::isDirectory($lang)) {
                $this->loadTranslationsFrom($lang, basename($dir));
            }
        }
    }
}
