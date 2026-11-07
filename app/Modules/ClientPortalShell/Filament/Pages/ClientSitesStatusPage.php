<?php

declare(strict_types=1);

namespace App\Modules\ClientPortalShell\Filament\Pages;

use App\Core\Concerns\HasModuleGate;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\TenantContext;
use App\Modules\ClientsSites\Models\Site;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

final class ClientSitesStatusPage extends Page
{
    use HasModuleGate;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static string $view = 'ClientPortalShell::filament.sites-status';

    protected static ?string $navigationLabel = 'Sites status';

    protected static ?string $title = 'Sites status';

    protected static ?string $slug = 'sites-status';

    public static function moduleId(): string
    {
        return 'client_portal';
    }

    public static function getNavigationGroup(): string
    {
        return 'Portal';
    }

    /**
     * @return Collection<int, Site>
     */
    public function getSitesProperty(): Collection
    {
        $tenant = TenantContext::get();
        if ($tenant === null) {
            return collect();
        }

        if (! app(ModuleManager::class)->enabled($tenant, 'clients_sites')) {
            return collect();
        }

        return Site::query()->where('is_active', true)->orderBy('name')->limit(50)->get();
    }
}
