<?php

declare(strict_types=1);

namespace App\Modules\Localisation\Filament\Pages;

use App\Core\Concerns\HasModuleGate;
use Filament\Pages\Page;

final class LocalesPage extends Page
{
    use HasModuleGate;

    protected static ?string $navigationIcon = 'heroicon-o-language';

    protected static string $view = 'Localisation::filament.locales';

    protected static ?string $navigationLabel = 'Locales';

    protected static ?string $title = 'Locales';

    protected static ?string $slug = 'locales';

    public static function moduleId(): string
    {
        return 'localisation';
    }

    public static function getNavigationGroup(): string
    {
        return 'Settings';
    }

    /**
     * @return list<string>
     */
    public function getLocalesProperty(): array
    {
        /** @var list<string> $locales */
        $locales = config('guardops.locales', ['en']);

        return $locales;
    }
}
