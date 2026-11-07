<?php

declare(strict_types=1);

namespace App\Modules\ClientPortalShell\Filament\Pages;

use App\Core\Concerns\HasModuleGate;
use App\Core\Tenancy\TenantContext;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

final class ClientPortalSettingsPage extends Page implements HasForms
{
    use HasModuleGate;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'ClientPortalShell::filament.portal-settings';

    protected static ?string $navigationLabel = 'Client portal settings';

    protected static ?string $title = 'Client portal settings';

    protected static ?string $slug = 'client-portal-settings';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public static function moduleId(): string
    {
        return 'client_portal';
    }

    public static function getNavigationGroup(): string
    {
        return 'Clients';
    }

    public function mount(): void
    {
        $tenant = TenantContext::require();
        $branding = $tenant->branding ?? [];
        $portal = is_array($branding['portal'] ?? null) ? $branding['portal'] : [];

        $this->form->fill([
            'welcome_message' => $portal['welcome_message'] ?? 'Welcome to your GuardOps client portal.',
            'show_guard_names' => (bool) ($portal['show_guard_names'] ?? true),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('welcome_message')->required()->rows(3),
                Forms\Components\Toggle::make('show_guard_names')->default(true),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $tenant = TenantContext::require();
        /** @var array{welcome_message: string, show_guard_names: bool} $state */
        $state = $this->form->getState();

        $branding = $tenant->branding ?? [];
        $branding['portal'] = [
            'welcome_message' => $state['welcome_message'],
            'show_guard_names' => (bool) $state['show_guard_names'],
        ];
        $tenant->branding = $branding;
        $tenant->save();

        Notification::make()->title('Client portal settings saved')->success()->send();
    }
}
