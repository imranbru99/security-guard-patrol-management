<?php

declare(strict_types=1);

namespace App\Modules\DemoToggle\Filament\Pages;

use App\Core\Concerns\HasModuleGate;
use Filament\Pages\Page;

final class DemoTogglePage extends Page
{
    use HasModuleGate;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static string $view = 'DemoToggle::filament.page';

    protected static ?string $navigationLabel = 'Demo Toggle';

    protected static ?string $title = 'Demo Toggle';

    protected static ?string $slug = 'demo-toggle';

    public static function moduleId(): string
    {
        return 'demo_toggle';
    }

    public static function getNavigationGroup(): string
    {
        return 'Platform';
    }
}
