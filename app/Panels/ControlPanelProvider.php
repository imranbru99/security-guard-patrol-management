<?php

declare(strict_types=1);

namespace App\Panels;

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\ControlRoom\Filament\Pages\AlertQueuePage;
use App\Modules\ControlRoom\Filament\Pages\LiveWallPage;
use App\Modules\ControlRoom\Filament\Resources\DispatchJobResource;
use App\Modules\Sos\Filament\Pages\SosControlRoomPage;
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

final class ControlPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('control')
            ->path('control')
            ->login()
            ->brandName('GuardOps Control')
            ->colors([
                'primary' => Color::Rose,
            ])
            ->discoverResources(in: app_path('Filament/Control/Resources'), for: 'App\\Filament\\Control\\Resources')
            ->discoverPages(in: app_path('Filament/Control/Pages'), for: 'App\\Filament\\Control\\Pages')
            ->resources([
                DispatchJobResource::class,
            ])
            ->pages([
                Pages\Dashboard::class,
                LiveWallPage::class,
                AlertQueuePage::class,
                SosControlRoomPage::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Control/Widgets'), for: 'App\\Filament\\Control\\Widgets')
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
