<?php

declare(strict_types=1);

namespace App\Modules\MediaVault\Services;

use App\Modules\MediaVault\Models\EvidenceCustodyEvent;
use App\Modules\MediaVault\Models\MediaAsset;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class MediaVaultService
{
    /**
     * @param  array<string, mixed>  $meta
     */
    public function storeWithHash(
        UploadedFile $file,
        ?int $actorId = null,
        string $disk = 'local',
        array $meta = [],
    ): MediaAsset {
        $contents = file_get_contents($file->getRealPath());
        if ($contents === false) {
            throw new \RuntimeException('Unable to read uploaded file.');
        }

        $sha256 = hash('sha256', $contents);
        $filename = $file->getClientOriginalName();
        $path = 'media-vault/'.now()->format('Y/m').'/'.Str::ulid().'_'.Str::slug(pathinfo($filename, PATHINFO_FILENAME)).'.'.$file->getClientOriginalExtension();

        Storage::disk($disk)->put($path, $contents);

        return DB::transaction(function () use ($disk, $path, $filename, $file, $sha256, $meta, $actorId): MediaAsset {
            $asset = MediaAsset::query()->create([
                'disk' => $disk,
                'path' => $path,
                'filename' => $filename,
                'mime' => $file->getMimeType(),
                'size' => $file->getSize() ?: strlen((string) Storage::disk($disk)->get($path)),
                'sha256' => $sha256,
                'meta' => $meta,
            ]);

            $prevHash = EvidenceCustodyEvent::query()
                ->where('media_asset_id', $asset->id)
                ->orderByDesc('id')
                ->value('hash');

            $payload = implode('|', [
                (string) $asset->id,
                'stored',
                (string) $actorId,
                $sha256,
                (string) $prevHash,
                now()->toIso8601String(),
            ]);

            EvidenceCustodyEvent::query()->create([
                'media_asset_id' => $asset->id,
                'action' => 'stored',
                'actor_id' => $actorId,
                'hash' => hash('sha256', $payload),
                'prev_hash' => $prevHash,
                'meta' => ['filename' => $filename],
            ]);

            return $asset;
        });
    }
}
