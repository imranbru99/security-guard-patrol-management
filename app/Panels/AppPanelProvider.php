<?php

declare(strict_types=1);

namespace App\Panels;

use App\Core\Http\Middleware\SetTenantFromUser;
use App\Modules\AiAssistants\Filament\Pages\AiAssistantsPage;
use App\Modules\Alarms\Filament\Resources\AlarmResource;
use App\Modules\Analytics\Filament\Pages\AnalyticsDashboard;
use App\Modules\Assets\Filament\Resources\AssetResource;
use App\Modules\AttendanceTracking\Filament\Pages\DeviceManagerPage;
use App\Modules\AttendanceTracking\Filament\Resources\AttendanceSummaryResource;
use App\Modules\AuditCompliance\Filament\Resources\AuditEventResource;
use App\Modules\Billing\Filament\Resources\InvoiceResource;
use App\Modules\Catalog\Filament\Resources\CatalogItemResource;
use App\Modules\CheckpointsTags\Filament\Pages\CheckpointTagSheetsPage;
use App\Modules\CheckpointsTags\Filament\Resources\CheckpointResource;
use App\Modules\ClientPortalShell\Filament\Pages\ClientPortalSettingsPage;
use App\Modules\ClientsSites\Filament\Resources\ClientResource;
use App\Modules\ClientsSites\Filament\Resources\PostResource;
use App\Modules\ClientsSites\Filament\Resources\SiteResource;
use App\Modules\ContractsSlas\Filament\Resources\ContractResource;
use App\Modules\Dar\Filament\Resources\DarResource;
use App\Modules\DemoToggle\Filament\Pages\DemoTogglePage;
use App\Modules\DisciplinePerformance\Filament\Resources\DisciplinaryRecordResource;
use App\Modules\DisciplinePerformance\Filament\Resources\PerformanceScoreResource;
use App\Modules\Emergency\Filament\Resources\EmergencyPlanResource;
use App\Modules\Expenses\Filament\Resources\ExpenseResource;
use App\Modules\GuardsEmployees\Filament\Resources\GuardResource;
use App\Modules\Hazards\Filament\Resources\HazardResource;
use App\Modules\Incidents\Filament\Resources\IncidentResource;
use App\Modules\Inspections\Filament\Resources\InspectionResource;
use App\Modules\Integrations\Filament\Pages\IntegrationsHubPage;
use App\Modules\KeyControl\Filament\Resources\KeyItemResource;
use App\Modules\LeadsCrm\Filament\Resources\LeadResource;
use App\Modules\LeadsCrm\Filament\Resources\QuoteResource;
use App\Modules\LeaveAvailability\Filament\Resources\LeaveRequestResource;
use App\Modules\LicencesDocuments\Filament\Resources\GuardLicenceResource;
use App\Modules\LicencesDocuments\Filament\Resources\LicenceTypeResource;
use App\Modules\LiveTracking\Filament\Pages\LiveTrackingPage;
use App\Modules\Localisation\Filament\Pages\LocalesPage;
use App\Modules\MediaVault\Filament\Resources\MediaAssetResource;
use App\Modules\Microsite\Filament\Resources\TenantPageResource;
use App\Modules\ModulePlans\Filament\Pages\ModulesPage;
use App\Modules\ModulePlans\Filament\Pages\PresetsPage;
use App\Modules\NotificationsMessaging\Filament\Resources\NotificationRuleResource;
use App\Modules\NotificationsMessaging\Filament\Resources\NotificationTemplateResource;
use App\Modules\PatrolMonitoring\Filament\Pages\PatrolMonitoringPage;
use App\Modules\PatrolRoutes\Filament\Resources\PatrolRouteResource;
use App\Modules\PayrollExport\Filament\Resources\PayrollRunResource;
use App\Modules\Privacy\Filament\Pages\PrivacyCentrePage;
use App\Modules\PublicApiWebhooks\Filament\Resources\WebhookEndpointResource;
use App\Modules\RecruitmentAts\Filament\Resources\ApplicationResource;
use App\Modules\ReportsDashboards\Filament\Pages\ReportBuilderPage;
use App\Modules\ReportsDashboards\Filament\Widgets\KpiPlaceholderWidget;
use App\Modules\SettingsForms\Filament\Pages\FormBuilderPage;
use App\Modules\SettingsForms\Filament\Resources\CustomFieldResource;
use App\Modules\SettingsForms\Filament\Resources\FormTemplateResource;
use App\Modules\ShiftHandover\Filament\Resources\HandoverResource;
use App\Modules\ShiftScheduling\Filament\Pages\RosterConflictPage;
use App\Modules\ShiftScheduling\Filament\Resources\ShiftAssignmentResource;
use App\Modules\ShiftScheduling\Filament\Resources\ShiftResource;
use App\Modules\SmartScheduling\Filament\Pages\SmartSchedulingPage;
use App\Modules\Sos\Filament\Resources\SosEventResource;
use App\Modules\TasksPostOrders\Filament\Resources\TaskItemResource;
use App\Modules\TenancyOrganisation\Filament\Pages\OrganisationSettingsPage;
use App\Modules\TenancyOrganisation\Filament\Resources\BranchResource;
use App\Modules\TenancyOrganisation\Filament\Resources\DepartmentResource;
use App\Modules\TrainingCertification\Filament\Resources\CourseResource;
use App\Modules\UsersSecurity\Filament\Pages\ApiTokensPage;
use App\Modules\UsersSecurity\Filament\Pages\RolesPage;
use App\Modules\UsersSecurity\Filament\Pages\SessionsPage;
use App\Modules\UsersSecurity\Filament\Resources\UserResource;
use App\Modules\VehiclesGate\Filament\Resources\VehicleLogResource;
use App\Modules\Visitors\Filament\Resources\VisitResource;
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

final class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('app')
            ->path('app')
            ->login()
            ->brandName('GuardOps')
            ->colors([
                'primary' => Color::Teal,
            ])
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            ->resources([
                BranchResource::class,
                DepartmentResource::class,
                UserResource::class,
                AuditEventResource::class,
                NotificationTemplateResource::class,
                NotificationRuleResource::class,
                CustomFieldResource::class,
                FormTemplateResource::class,
                MediaAssetResource::class,
                WebhookEndpointResource::class,
                ClientResource::class,
                SiteResource::class,
                PostResource::class,
                ContractResource::class,
                LeadResource::class,
                QuoteResource::class,
                GuardResource::class,
                LicenceTypeResource::class,
                GuardLicenceResource::class,
                CourseResource::class,
                ApplicationResource::class,
                LeaveRequestResource::class,
                DisciplinaryRecordResource::class,
                PerformanceScoreResource::class,
                PayrollRunResource::class,
                ShiftResource::class,
                ShiftAssignmentResource::class,
                AttendanceSummaryResource::class,
                HandoverResource::class,
                CheckpointResource::class,
                PatrolRouteResource::class,
                IncidentResource::class,
                DarResource::class,
                SosEventResource::class,
                AlarmResource::class,
                EmergencyPlanResource::class,
                HazardResource::class,
                VisitResource::class,
                VehicleLogResource::class,
                KeyItemResource::class,
                AssetResource::class,
                InspectionResource::class,
                TaskItemResource::class,
                CatalogItemResource::class,
                InvoiceResource::class,
                ExpenseResource::class,
                TenantPageResource::class,
            ])
            ->pages([
                Pages\Dashboard::class,
                DemoTogglePage::class,
                OrganisationSettingsPage::class,
                RolesPage::class,
                SessionsPage::class,
                ApiTokensPage::class,
                ModulesPage::class,
                PresetsPage::class,
                FormBuilderPage::class,
                LocalesPage::class,
                ReportBuilderPage::class,
                ClientPortalSettingsPage::class,
                RosterConflictPage::class,
                DeviceManagerPage::class,
                CheckpointTagSheetsPage::class,
                PatrolMonitoringPage::class,
                LiveTrackingPage::class,
                AiAssistantsPage::class,
                SmartSchedulingPage::class,
                AnalyticsDashboard::class,
                IntegrationsHubPage::class,
                PrivacyCentrePage::class,
            ])
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\\Filament\\App\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                KpiPlaceholderWidget::class,
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
