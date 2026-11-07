<?php

declare(strict_types=1);

namespace App\Modules\Privacy\Http\Controllers;

use App\Modules\Privacy\Models\Consent;
use App\Modules\Privacy\Models\DsarRequest;
use App\Modules\Privacy\Models\RetentionPolicy;
use Illuminate\Http\JsonResponse;

final class PrivacyController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'privacy',
            'status' => 'enabled',
            'counts' => [
                'consents' => Consent::query()->count(),
                'retention_policies' => RetentionPolicy::query()->count(),
                'dsar_requests' => DsarRequest::query()->count(),
            ],
        ]);
    }
}
