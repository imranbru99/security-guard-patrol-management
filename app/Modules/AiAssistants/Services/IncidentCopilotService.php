<?php

declare(strict_types=1);

namespace App\Modules\AiAssistants\Services;

use App\Core\Tenancy\Tenant;
use App\Modules\AiAssistants\Models\AiDraft;
use ImranDevBd\AiHub\Facades\AIHub;
use Throwable;

final class IncidentCopilotService
{
    /**
     * @param  array<string, mixed>  $facts
     * @return array{draft: AiDraft, result: array<string, mixed>}
     */
    public function draft(Tenant $tenant, array $facts, ?int $userId = null): array
    {
        $jobTag = 'tenant:'.$tenant->id.':ai_assistants';
        $schema = [
            'type' => 'object',
            'properties' => [
                'summary' => ['type' => 'string'],
                'severity_suggestion' => ['type' => 'string'],
                'recommended_actions' => ['type' => 'array', 'items' => ['type' => 'string']],
            ],
            'required' => ['summary', 'severity_suggestion', 'recommended_actions'],
        ];

        $canned = [
            'summary' => 'Draft incident summary based on provided facts (review required).',
            'severity_suggestion' => 'medium',
            'recommended_actions' => [
                'Secure the scene',
                'Notify site supervisor',
                'Capture evidence photos',
            ],
        ];

        $provider = 'canned';
        $model = 'local-stub';
        $result = $canned;

        if (class_exists(AIHub::class)) {
            try {
                if (method_exists(AIHub::class, 'fake')) {
                    AIHub::fake(json_encode($canned, JSON_THROW_ON_ERROR));
                }

                $response = AIHub::prompt('Draft an incident report as JSON for: '.json_encode($facts))
                    ->asJsonSchema($schema, 'incident_draft')
                    ->forJob($jobTag)
                    ->send();

                $decoded = $response->json();
                if (is_array($decoded)) {
                    $result = $decoded;
                }
                $provider = $response->provider ?: 'aihub';
                $model = $response->model ?: 'aihub';
            } catch (Throwable) {
                $result = $canned;
                $provider = 'canned';
                $model = 'local-stub';
            }
        }

        $draft = AiDraft::query()->create([
            'tenant_id' => $tenant->id,
            'feature' => 'incident_copilot',
            'status' => 'draft',
            'input' => $facts,
            'output' => $result,
            'provider' => $provider,
            'model' => $model,
            'job_tag' => $jobTag,
            'created_by' => $userId,
        ]);

        return ['draft' => $draft, 'result' => $result];
    }
}
