<?php

declare(strict_types=1);

namespace App\Core\Modules;

use App\Core\Modules\Models\Module;
use App\Core\Modules\Models\TenantModule;
use App\Core\Tenancy\Tenant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

final class ModuleManager
{
    public function __construct(
        private readonly ManifestRegistry $registry,
    ) {}

    /**
     * @return array<string, bool>
     */
    public function mapFor(Tenant $tenant): array
    {
        $cacheKey = "tenant:{$tenant->id}:modules";

        return Cache::remember($cacheKey, now()->addHour(), function () use ($tenant): array {
            $modules = Module::query()->orderBy('sort_order')->get();
            $enabledKeys = TenantModule::query()
                ->where('tenant_id', $tenant->id)
                ->where('enabled', true)
                ->pluck('module_id')
                ->all();

            $map = [];
            foreach ($modules as $module) {
                $map[$module->key] = $module->is_core || in_array($module->id, $enabledKeys, true);
            }

            return $map;
        });
    }

    public function bustCache(Tenant $tenant): void
    {
        Cache::forget("tenant:{$tenant->id}:modules");
    }

    public function enabled(Tenant $tenant, string $moduleKey): bool
    {
        $map = $this->mapFor($tenant);

        if (! array_key_exists($moduleKey, $map)) {
            return false;
        }

        return $map[$moduleKey];
    }

    /**
     * @return Collection<int, Module>
     */
    public function all(): Collection
    {
        return Module::query()->orderBy('group')->orderBy('sort_order')->get();
    }

    /**
     * @return array{will_enable: list<string>, will_disable: list<string>, blocked_by: list<string>}
     */
    public function dryRunEnable(Tenant $tenant, string $moduleKey): array
    {
        $module = $this->findOrFail($moduleKey);
        $toEnable = $this->resolveDependencies($moduleKey);
        $blocked = [];

        foreach ($module->conflictKeys() as $conflict) {
            if ($this->enabled($tenant, $conflict)) {
                $blocked[] = $conflict;
            }
        }

        return [
            'will_enable' => $toEnable,
            'will_disable' => [],
            'blocked_by' => $blocked,
        ];
    }

    /**
     * @return array{will_enable: list<string>, will_disable: list<string>, blocked_by: list<string>}
     */
    public function dryRunDisable(Tenant $tenant, string $moduleKey): array
    {
        $module = $this->findOrFail($moduleKey);

        if ($module->is_core) {
            throw new InvalidArgumentException("Core module [{$moduleKey}] cannot be disabled.");
        }

        $dependents = Module::query()
            ->get()
            ->filter(fn (Module $m): bool => in_array($moduleKey, $m->dependencyKeys(), true))
            ->pluck('key')
            ->filter(fn (string $key): bool => $this->enabled($tenant, $key))
            ->values()
            ->all();

        return [
            'will_enable' => [],
            'will_disable' => [$moduleKey],
            'blocked_by' => $dependents,
        ];
    }

    public function enable(Tenant $tenant, string $moduleKey, string $source = 'manual'): void
    {
        $plan = $this->dryRunEnable($tenant, $moduleKey);

        if ($plan['blocked_by'] !== []) {
            throw new RuntimeException(
                'Cannot enable module due to conflicts: '.implode(', ', $plan['blocked_by'])
            );
        }

        DB::transaction(function () use ($tenant, $plan, $source): void {
            foreach ($plan['will_enable'] as $key) {
                $module = $this->findOrFail($key);
                TenantModule::query()->updateOrCreate(
                    [
                        'tenant_id' => $tenant->id,
                        'module_id' => $module->id,
                    ],
                    [
                        'enabled' => true,
                        'enabled_at' => now(),
                        'source' => $source,
                    ]
                );
            }
        });

        $this->bustCache($tenant);
    }

    public function disable(Tenant $tenant, string $moduleKey): void
    {
        $plan = $this->dryRunDisable($tenant, $moduleKey);

        if ($plan['blocked_by'] !== []) {
            throw new RuntimeException(
                'Cannot disable module; required by: '.implode(', ', $plan['blocked_by'])
            );
        }

        $module = $this->findOrFail($moduleKey);

        TenantModule::query()
            ->where('tenant_id', $tenant->id)
            ->where('module_id', $module->id)
            ->update([
                'enabled' => false,
                'enabled_at' => null,
            ]);

        $this->bustCache($tenant);
    }

    /**
     * @return list<string>
     */
    public function resolveDependencies(string $moduleKey): array
    {
        $resolved = [];
        $stack = [$moduleKey];

        while ($stack !== []) {
            $key = array_pop($stack);
            if (in_array($key, $resolved, true)) {
                continue;
            }
            $module = $this->findOrFail($key);
            foreach (array_reverse($module->dependencyKeys()) as $dep) {
                $stack[] = $dep;
            }
            $resolved[] = $key;
        }

        return array_reverse($resolved);
    }

    public function findOrFail(string $moduleKey): Module
    {
        /** @var Module|null $module */
        $module = Module::query()->where('key', $moduleKey)->first();

        if ($module === null) {
            // Fall back to in-memory manifest before DB sync
            $manifest = $this->registry->get($moduleKey);
            if ($manifest === null) {
                throw new InvalidArgumentException("Unknown module [{$moduleKey}].");
            }
            throw new InvalidArgumentException(
                "Module [{$moduleKey}] is registered in manifests but not synced. Run modules:sync."
            );
        }

        return $module;
    }
}
