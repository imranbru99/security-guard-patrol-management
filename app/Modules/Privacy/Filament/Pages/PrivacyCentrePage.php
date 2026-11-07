<?php

declare(strict_types=1);

namespace App\Modules\Privacy\Filament\Pages;

use App\Core\Concerns\HasModuleGate;
use App\Modules\Privacy\Models\Consent;
use App\Modules\Privacy\Models\DsarRequest;
use App\Modules\Privacy\Models\RetentionPolicy;
use Filament\Pages\Page;

final class PrivacyCentrePage extends Page
{
    use HasModuleGate;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static string $view = 'Privacy::filament.privacy-centre';

    protected static ?string $navigationLabel = 'Privacy centre';

    protected static ?string $title = 'Privacy centre';

    protected static ?string $slug = 'privacy-centre';

    public static function moduleId(): string
    {
        return 'privacy';
    }

    public static function getNavigationGroup(): string
    {
        return 'Commerce';
    }

    public function getConsentCountProperty(): int
    {
        return Consent::query()->count();
    }

    public function getPolicyCountProperty(): int
    {
        return RetentionPolicy::query()->count();
    }

    public function getDsarCountProperty(): int
    {
        return DsarRequest::query()->count();
    }
}
