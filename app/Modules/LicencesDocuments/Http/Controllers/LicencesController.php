<?php

declare(strict_types=1);

namespace App\Modules\LicencesDocuments\Http\Controllers;

use App\Modules\LicencesDocuments\Models\GuardLicence;
use Illuminate\Http\JsonResponse;

final class LicencesController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'licences',
            'status' => 'enabled',
            'note' => 'Review guard licence expiry dates regularly.',
        ]);
    }

    public function index(): JsonResponse
    {
        $licences = GuardLicence::query()->orderBy('expires_at')->get([
            'ulid', 'guard_id', 'licence_type_id', 'number', 'expires_at',
        ]);

        return response()->json(['data' => $licences]);
    }
}
