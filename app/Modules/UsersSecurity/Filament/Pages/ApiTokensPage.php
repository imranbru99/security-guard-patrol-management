<?php

declare(strict_types=1);

namespace App\Modules\UsersSecurity\Filament\Pages;

use App\Core\Concerns\HasModuleGate;
use Filament\Pages\Page;

final class ApiTokensPage extends Page
{
    use HasModuleGate;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static string $view = 'UsersSecurity::filament.api-tokens';

    protected static ?string $navigationLabel = 'API tokens';

    protected static ?string $title = 'API tokens';

    protected static ?string $slug = 'api-tokens';

    public static function moduleId(): string
    {
        return 'users_security';
    }

    public static function getNavigationGroup(): string
    {
        return 'Security';
    }
}
