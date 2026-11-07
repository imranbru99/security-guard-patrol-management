<?php

declare(strict_types=1);

namespace App\Modules\ModulePlans\Filament\Pages;

use App\Core\Concerns\HasModuleGate;
use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\TenantContext;
use App\Modules\ModulePlans\Support\BusinessPresets;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Throwable;

final class PresetsPage extends Page
{
    use HasModuleGate;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static string $view = 'ModulePlans::filament.presets';

    protected static ?string $navigationLabel = 'Business presets';

    protected static ?string $title = 'Business presets';

    protected static ?string $slug = 'business-presets';

    /** @var array<string, array{label: string, modules: list<string>}> */
    public array $presets = [];

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
        $this->presets = BusinessPresets::all();
    }

    public function applyPreset(string $presetKey): void
    {
        $presets = BusinessPresets::all();
        if (! isset($presets[$presetKey])) {
            Notification::make()->title('Unknown preset')->danger()->send();

            return;
        }

        $tenant = TenantContext::require();
        $manager = app(ModuleManager::class);
        $moduleKeys = $presets[$presetKey]['modules'];

        if ($moduleKeys === ['*']) {
            $moduleKeys = Module::query()->pluck('key')->all();
        }

        $enabled = 0;
        $skipped = 0;

        foreach ($moduleKeys as $key) {
            if (! Module::query()->where('key', $key)->exists()) {
                $skipped++;

                continue;
            }

            try {
                if (! $manager->enabled($tenant, $key)) {
                    $manager->enable($tenant, $key, 'preset:'.$presetKey);
                    $enabled++;
                }
            } catch (Throwable) {
                $skipped++;
            }
        }

        Notification::make()
            ->title("Preset applied: {$presets[$presetKey]['label']}")
            ->body("Enabled {$enabled} module(s); skipped {$skipped}.")
            ->success()
            ->send();
    }
}
