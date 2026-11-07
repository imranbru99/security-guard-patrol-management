<?php

declare(strict_types=1);

namespace App\Modules\PublicApiWebhooks\Services;

use App\Modules\PublicApiWebhooks\Models\WebhookDeliveryLog;
use App\Modules\PublicApiWebhooks\Models\WebhookEndpoint;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

final class OutboundWebhookDispatcher
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function dispatch(WebhookEndpoint $endpoint, string $event, array $payload): WebhookDeliveryLog
    {
        $body = json_encode([
            'event' => $event,
            'payload' => $payload,
            'sent_at' => now()->toIso8601String(),
        ], JSON_THROW_ON_ERROR);

        $signature = $this->sign($body, (string) $endpoint->secret);

        $responseStatus = null;
        $responseBody = null;
        $success = false;

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'X-GuardOps-Signature' => $signature,
                    'X-GuardOps-Event' => $event,
                ])
                ->withBody($body, 'application/json')
                ->post($endpoint->url);

            $responseStatus = $response->status();
            $responseBody = Str::limit($response->body(), 2000);
            $success = $response->successful();
        } catch (\Throwable $e) {
            $responseBody = $e->getMessage();
            $success = false;
        }

        return WebhookDeliveryLog::query()->create([
            'tenant_id' => $endpoint->tenant_id,
            'webhook_endpoint_id' => $endpoint->id,
            'event' => $event,
            'payload' => $payload,
            'response_status' => $responseStatus,
            'response_body' => $responseBody,
            'success' => $success,
        ]);
    }

    public function sign(string $body, string $secret): string
    {
        return hash_hmac('sha256', $body, $secret);
    }
}
