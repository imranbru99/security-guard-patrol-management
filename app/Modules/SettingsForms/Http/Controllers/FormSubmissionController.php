<?php

declare(strict_types=1);

namespace App\Modules\SettingsForms\Http\Controllers;

use App\Modules\SettingsForms\Models\FormSubmission;
use App\Modules\SettingsForms\Models\FormTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class FormSubmissionController
{
    public function store(Request $request, string $ulid): JsonResponse
    {
        /** @var FormTemplate $template */
        $template = FormTemplate::query()
            ->where('ulid', $ulid)
            ->where('is_active', true)
            ->firstOrFail();

        $validated = $request->validate([
            'payload' => ['required', 'array'],
        ]);

        $submission = FormSubmission::query()->create([
            'form_template_id' => $template->id,
            'submitted_by' => $request->user()?->id,
            'payload' => $validated['payload'],
        ]);

        return response()->json([
            'module' => 'settings_forms',
            'ulid' => $submission->ulid,
            'form_template_ulid' => $template->ulid,
            'submitted_at' => $submission->submitted_at?->toIso8601String(),
        ], 201);
    }

    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'settings_forms',
            'status' => 'enabled',
        ]);
    }
}
