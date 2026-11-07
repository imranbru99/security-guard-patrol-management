<?php

declare(strict_types=1);

namespace App\Modules\Integrations\Filament\Pages;

use App\Core\Concerns\HasModuleGate;
use App\Modules\Integrations\Models\IntegrationConnection;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

final class IntegrationsHubPage extends Page
{
    use HasModuleGate;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static string $view = 'Integrations::filament.integrations-hub';

    protected static ?string $navigationLabel = 'Integrations';

    protected static ?string $title = 'Integrations Hub';

    protected static ?string $slug = 'integrations';

    public static function moduleId(): string
    {
        return 'integrations';
    }

    public static function getNavigationGroup(): string
    {
        return 'Commerce';
    }

    /** @return Collection<int, IntegrationConnection> */
    public function getConnectionsProperty(): Collection
    {
        return IntegrationConnection::query()->orderBy('name')->limit(50)->get();
    }
}
