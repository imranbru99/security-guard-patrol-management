<?php

declare(strict_types=1);

namespace App\Modules\TenancyOrganisation\Http\Controllers;

use App\Modules\TenancyOrganisation\Models\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class BranchController
{
    public function index(): JsonResponse
    {
        $branches = Branch::query()->orderBy('name')->get([
            'ulid', 'name', 'slug', 'region', 'parent_id', 'is_active',
        ]);

        return response()->json(['data' => $branches]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'parent_ulid' => ['nullable', 'string'],
        ]);

        $parentId = null;
        if (! empty($data['parent_ulid'])) {
            $parentId = Branch::query()->where('ulid', $data['parent_ulid'])->value('id');
        }

        $branch = Branch::query()->create([
            'name' => $data['name'],
            'slug' => $data['slug'] ?? Str::slug($data['name']),
            'region' => $data['region'] ?? null,
            'parent_id' => $parentId,
        ]);

        return response()->json(['data' => $branch], 201);
    }

    public function show(string $ulid): JsonResponse
    {
        $branch = Branch::query()->where('ulid', $ulid)->firstOrFail();

        return response()->json(['data' => $branch]);
    }

    public function update(Request $request, string $ulid): JsonResponse
    {
        $branch = Branch::query()->where('ulid', $ulid)->firstOrFail();

        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'slug' => ['sometimes', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $branch->fill($data)->save();

        return response()->json(['data' => $branch->refresh()]);
    }

    public function destroy(string $ulid): JsonResponse
    {
        $branch = Branch::query()->where('ulid', $ulid)->firstOrFail();
        $branch->delete();

        return response()->json(null, 204);
    }
}
