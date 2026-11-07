<?php

declare(strict_types=1);

namespace App\Modules\ModulePlans\Http\Controllers;

use App\Core\Modules\Models\Module;
use App\Core\Modules\ModuleManager;
use App\Core\Tenancy\TenantContext;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

final class ModuleToggleController
{
    public function __invoke(Request $request, ModuleManager $manager): JsonResponse
    {
        $data = $request->validate([
            'module' => ['required', 'string'],
            'enabled' => ['required', 'boolean'],
        ]);

        $tenant = TenantContext::require();
        $key = $data['module'];
        $module = Module::query()->where('key', $key)->firstOrFail();

        if ($module->is_core && $data['enabled'] === false) {
            return response()->json(['message' => 'Core modules cannot be disabled.'], 422);
        }

        try {
            if ($data['enabled']) {
                $manager->enable($tenant, $key, 'api');
            } else {
                $manager->disable($tenant, $key);
            }
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json([
            'module' => $key,
            'enabled' => $manager->enabled($tenant, $key),
        ]);
    }
}
