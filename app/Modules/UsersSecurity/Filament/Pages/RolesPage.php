<?php

declare(strict_types=1);

namespace App\Modules\UsersSecurity\Filament\Pages;

use App\Core\Concerns\HasModuleGate;
use App\Core\Tenancy\TenantContext;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Spatie\Permission\Models\Role;

final class RolesPage extends Page implements HasForms
{
    use HasModuleGate;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-finger-print';

    protected static string $view = 'UsersSecurity::filament.roles';

    protected static ?string $navigationLabel = 'Roles';

    protected static ?string $title = 'Roles';

    protected static ?string $slug = 'roles';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    /** @var list<array{id: int, name: string, tenant_id: int|null}> */
    public array $roles = [];

    public static function moduleId(): string
    {
        return 'users_security';
    }

    public static function getNavigationGroup(): string
    {
        return 'Security';
    }

    public function mount(): void
    {
        $this->refreshRoles();
        $this->form->fill(['name' => '']);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Role name')
                    ->required()
                    ->maxLength(125),
            ])
            ->statePath('data');
    }

    public function createRole(): void
    {
        $tenant = TenantContext::require();
        /** @var array{name: string} $state */
        $state = $this->form->getState();

        Role::query()->create([
            'name' => $state['name'],
            'guard_name' => 'web',
            'tenant_id' => $tenant->id,
        ]);

        $this->form->fill(['name' => '']);
        $this->refreshRoles();

        Notification::make()->title('Role created')->success()->send();
    }

    private function refreshRoles(): void
    {
        $tenantId = TenantContext::id();

        $this->roles = Role::query()
            ->where('guard_name', 'web')
            ->where(function ($q) use ($tenantId): void {
                $q->whereNull('tenant_id')->orWhere('tenant_id', $tenantId);
            })
            ->orderBy('name')
            ->get(['id', 'name', 'tenant_id'])
            ->map(fn (Role $role): array => [
                'id' => (int) $role->id,
                'name' => (string) $role->name,
                'tenant_id' => $role->tenant_id !== null ? (int) $role->tenant_id : null,
            ])
            ->all();
    }
}
