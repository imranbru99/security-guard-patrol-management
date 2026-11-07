<?php

declare(strict_types=1);

namespace App\Modules\AiAssistants\Http\Controllers;

use App\Core\Tenancy\TenantContext;
use App\Modules\AiAssistants\Services\IncidentCopilotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class AiAssistantsController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'ai_assistants',
            'status' => 'enabled',
        ]);
    }

    public function draftIncident(Request $request, IncidentCopilotService $service): JsonResponse
    {
        $tenant = TenantContext::get();
        abort_if($tenant === null, 404);

        $facts = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        $out = $service->draft($tenant, $facts, $request->user()?->id);

        return response()->json([
            'module' => 'ai_assistants',
            'draft_ulid' => $out['draft']->ulid,
            'status' => 'draft',
            'result' => $out['result'],
        ]);
    }
}
