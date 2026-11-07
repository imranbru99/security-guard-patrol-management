<?php

declare(strict_types=1);

namespace App\Modules\Incidents\Services;

use App\Modules\Incidents\Models\IncidentMedia;
use App\Modules\MediaVault\Models\EvidenceCustodyEvent;
use App\Modules\MediaVault\Models\MediaAsset;

final class IncidentEvidenceAccessLogger
{
    public function logView(IncidentMedia $media, ?int $actorId = null): ?EvidenceCustodyEvent
    {
        if (! class_exists(EvidenceCustodyEvent::class)) {
            return null;
        }

        $asset = $media->mediaAsset;
        if (! $asset instanceof MediaAsset) {
            return null;
        }

        $prevHash = EvidenceCustodyEvent::query()
            ->where('media_asset_id', $asset->id)
            ->orderByDesc('id')
            ->value('hash');

        $payload = implode('|', [
            (string) $asset->id,
            'accessed',
            (string) $actorId,
            (string) $media->id,
            (string) $prevHash,
            now()->toIso8601String(),
        ]);

        return EvidenceCustodyEvent::query()->create([
            'media_asset_id' => $asset->id,
            'action' => 'accessed',
            'actor_id' => $actorId,
            'hash' => hash('sha256', $payload),
            'prev_hash' => $prevHash,
            'meta' => [
                'incident_media_ulid' => $media->ulid,
                'incident_id' => $media->incident_id,
                'context' => 'incident_media_view',
            ],
        ]);
    }
}
