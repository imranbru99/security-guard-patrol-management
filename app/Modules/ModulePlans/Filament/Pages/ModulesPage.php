<?php

declare(strict_types=1);

namespace App\Modules\ModulePlans\Filament\Pages;

use App\Core\Concerns\HasModuleGate;
use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\TenantContext;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Throwable;

final class ModulesPage extends Page
{
    use HasModuleGate;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static string $view = 'ModulePlans::filament.modules';

    protected static ?string $navigationLabel = 'Modules';

    protected static ?string $title = 'Modules';

    protected static ?string $slug = 'modules';

    /** @var list<array{key: string, name: string, group: string, description: string|null, is_core: bool, enabled: bool}> */
    public array $modules = [];

    public static function moduleId(): string
    {
        return 'module_plans';
    }

    public static function getNavigationGroup(): string
    {
        return 'Platform';
    }

    public function mount(): void
    {
        $this->refreshModules();
    }

    public function toggle(string $moduleKey): void
    {
        $tenant = TenantContext::require();
        $manager = app(ModuleManager::class);
        $module = Module::query()->where('key', $moduleKey)->firstOrFail();

        if ($module->is_core) {
            Notification::make()
                ->title('Core modules cannot be disabled')
                ->warning()
                ->send();

            return;
        }

        try {
            if ($manager->enabled($tenant, $moduleKey)) {
                $manager->disable($tenant, $moduleKey);
                Notification::make()->title("Disabled {$module->name}")->success()->send();
            } else {
                $manager->enable($tenant, $moduleKey, 'manual');
                Notification::make()->title("Enabled {$module->name}")->success()->send();
            }
        } catch (Throwable $e) {
            Notification::make()->title($e->getMessage())->danger()->send();
        }

        $this->refreshModules();
    }

    private function refreshModules(): void
    {
        $tenant = TenantContext::require();
        $manager = app(ModuleManager::class);

        $this->modules = Module::query()
            ->orderBy('group')
            ->orderBy('sort_order')
            ->get()
            ->map(fn (Module $module): array => [
                'key' => $module->key,
                'name' => $module->name,
                'group' => $module->group,
                'description' => $module->description,
                'is_core' => (bool) $module->is_core,
                'enabled' => $manager->enabled($tenant, $module->key),
            ])
            ->all();
    }
}
