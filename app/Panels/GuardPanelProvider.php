<?php

declare(strict_types=1);

namespace App\Panels;

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\Incidents\Filament\Pages\GuardReportIncidentPage;
use App\Modules\MobilePwa\Filament\Pages\PwaStatusPage;
use App\Modules\PatrolExecution\Filament\Pages\MyPatrolPage;
use App\Modules\Sos\Filament\Pages\GuardSosPage;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

final class GuardPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('guard')
            ->path('guard')
            ->login()
            ->brandName('GuardOps Guard')
            ->colors([
                'primary' => Color::Emerald,
            ])
            ->discoverResources(in: app_path('Filament/Guard/Resources'), for: 'App\\Filament\\Guard\\Resources')
            ->discoverPages(in: app_path('Filament/Guard/Pages'), for: 'App\\Filament\\Guard\\Pages')
            ->pages([
                Pages\Dashboard::class,
                MyPatrolPage::class,
                GuardReportIncidentPage::class,
                GuardSosPage::class,
                PwaStatusPage::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Guard/Widgets'), for: 'App\\Filament\\Guard\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                SetTenantFromUser::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
