<?php

declare(strict_types=1);

namespace App\Modules\TenancyOrganisation\Filament\Pages;

use App\Core\Concerns\HasModuleGate;
use App\Core\Tenancy\TenantContext;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

final class OrganisationSettingsPage extends Page implements HasForms
{
    use HasModuleGate;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static string $view = 'TenancyOrganisation::filament.organisation-settings';

    protected static ?string $navigationLabel = 'Organisation settings';

    protected static ?string $title = 'Organisation settings';

    protected static ?string $slug = 'organisation-settings';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public static function moduleId(): string
    {
        return 'tenancy';
    }

    public static function getNavigationGroup(): string
    {
        return 'Organisation';
    }

    public function mount(): void
    {
        $tenant = TenantContext::require();
        $branding = $tenant->branding ?? [];

        $this->form->fill([
            'name' => $tenant->name,
            'legal_name' => $tenant->legal_name,
            'logo_url' => $branding['logo_url'] ?? null,
            'primary_color' => $branding['primary_color'] ?? '#0d9488',
            'accent_color' => $branding['accent_color'] ?? '#134e4a',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                Forms\Components\TextInput::make('legal_name')->maxLength(255),
                Forms\Components\TextInput::make('logo_url')->label('Logo URL')->url()->maxLength(2048),
                Forms\Components\ColorPicker::make('primary_color'),
                Forms\Components\ColorPicker::make('accent_color'),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $tenant = TenantContext::require();
        /** @var array{name: string, legal_name?: string|null, logo_url?: string|null, primary_color?: string|null, accent_color?: string|null} $state */
        $state = $this->form->getState();

        $tenant->name = $state['name'];
        $tenant->legal_name = $state['legal_name'] ?? null;
        $tenant->branding = array_merge($tenant->branding ?? [], [
            'logo_url' => $state['logo_url'] ?? null,
            'primary_color' => $state['primary_color'] ?? null,
            'accent_color' => $state['accent_color'] ?? null,
        ]);
        $tenant->save();

        Notification::make()->title('Organisation settings saved')->success()->send();
    }
}
