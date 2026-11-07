<?php

declare(strict_types=1);

namespace App\Modules\MediaVault\Http\Controllers;

use App\Modules\MediaVault\Models\MediaAsset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class MediaVaultController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'media_vault',
            'status' => 'enabled',
        ]);
    }

    public function download(string $ulid): StreamedResponse
    {
        /** @var MediaAsset $asset */
        $asset = MediaAsset::query()->where('ulid', $ulid)->firstOrFail();

        return Storage::disk($asset->disk)->download($asset->path, $asset->filename);
    }
}
