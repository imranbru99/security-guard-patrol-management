<?php

declare(strict_types=1);

namespace App\Core\Modules;

use Illuminate\Support\Facades\File;
use InvalidArgumentException;

final class ManifestRegistry
{
    /** @var array<string, array<string, mixed>>|null */
    private ?array $manifests = null;

    /**
     * @return array<string, array<string, mixed>>
     */
    public function all(): array
    {
        if ($this->manifests === null) {
            $this->manifests = $this->discover();
        }

        return $this->manifests;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function get(string $id): ?array
    {
        return $this->all()[$id] ?? null;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function discover(): array
    {
        $path = app_path('Modules');
        if (! File::isDirectory($path)) {
            return [];
        }

        $manifests = [];
        foreach (File::directories($path) as $dir) {
            $file = $dir.'/module.php';
            if (! File::exists($file)) {
                continue;
            }

            /** @var array<string, mixed> $manifest */
            $manifest = require $file;
            $this->assertValid($manifest, $file);
            $manifests[$manifest['id']] = $manifest;
        }

        ksort($manifests);

        return $manifests;
    }

    /**
     * @param  array<string, mixed>  $manifest
     */
    private function assertValid(array $manifest, string $file): void
    {
        $required = [
            'id', 'name', 'group', 'icon', 'description', 'features',
            'depends_on', 'conflicts_with', 'permissions', 'filament_resources',
            'api_scopes', 'scheduled_jobs', 'widgets', 'settings_schema',
            'default_on_presets', 'plan_tier', 'price', 'public_page', 'demo_seeder',
        ];

        foreach ($required as $key) {
            if (! array_key_exists($key, $manifest)) {
                throw new InvalidArgumentException("Module manifest [{$file}] missing key [{$key}].");
            }
        }
    }
}
