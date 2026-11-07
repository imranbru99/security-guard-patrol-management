<?php

declare(strict_types=1);

namespace App\Modules\PublicApiWebhooks\Http\Controllers;

use App\Modules\PublicApiWebhooks\Services\OutboundWebhookDispatcher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class WebhookController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'public_api',
            'status' => 'enabled',
        ]);
    }

    public function ping(Request $request, OutboundWebhookDispatcher $dispatcher): JsonResponse
    {
        $body = json_encode([
            'event' => 'webhook.ping',
            'payload' => ['ok' => true],
            'sent_at' => now()->toIso8601String(),
        ], JSON_THROW_ON_ERROR);

        $secret = (string) $request->input('secret', 'test-secret');
        $signature = $dispatcher->sign($body, $secret);

        return response()->json([
            'module' => 'public_api',
            'event' => 'webhook.ping',
            'signature_header' => 'X-GuardOps-Signature',
            'signature' => $signature,
            'body' => json_decode($body, true),
        ]);
    }
}
