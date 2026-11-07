<?php

declare(strict_types=1);

namespace App\Core\Modules;

use App\Core\Modules\Models\Module;
use Illuminate\Console\Command;

final class SyncModulesCommand extends Command
{
    protected $signature = 'modules:sync';

    protected $description = 'Sync module manifests from app/Modules/*/module.php into the modules table';

    public function handle(ManifestRegistry $registry): int
    {
        $sort = 0;
        foreach ($registry->all() as $id => $manifest) {
            $sort++;
            Module::query()->updateOrCreate(
                ['key' => $id],
                [
                    'name' => is_array($manifest['name']) ? ($manifest['name']['en'] ?? $id) : (string) $manifest['name'],
                    'group' => (string) $manifest['group'],
                    'icon' => (string) $manifest['icon'],
                    'description' => is_array($manifest['description'])
                        ? ($manifest['description']['en'] ?? '')
                        : (string) $manifest['description'],
                    'features' => $manifest['features'],
                    'depends_on' => $manifest['depends_on'],
                    'conflicts_with' => $manifest['conflicts_with'],
                    'permissions' => $manifest['permissions'],
                    'plan_tier' => (string) $manifest['plan_tier'],
                    'price_cents' => (int) round(((float) $manifest['price']) * 100),
                    'is_core' => ($manifest['plan_tier'] ?? '') === 'core',
                    'manifest' => $manifest,
                    'public_page' => $manifest['public_page'],
                    'sort_order' => $sort,
                ]
            );
            $this->line("Synced: {$id}");
        }

        $this->info('Modules synced: '.count($registry->all()));

        return self::SUCCESS;
    }
}
