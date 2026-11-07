<?php

declare(strict_types=1);

namespace App\Modules\MobilePwa\Filament\Pages;

use App\Core\Concerns\HasModuleGate;
use Filament\Pages\Page;

final class PwaStatusPage extends Page
{
    use HasModuleGate;

    protected static ?string $navigationIcon = 'heroicon-o-device-phone-mobile';

    protected static string $view = 'MobilePwa::filament.pwa-status';

    protected static ?string $navigationLabel = 'PWA status';

    protected static ?string $title = 'PWA status';

    protected static ?string $slug = 'pwa-status';

    public static function moduleId(): string
    {
        return 'mobile_pwa';
    }

    public static function getNavigationGroup(): string
    {
        return 'Commerce';
    }
}
