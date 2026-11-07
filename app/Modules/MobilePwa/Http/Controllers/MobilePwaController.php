<?php

declare(strict_types=1);

namespace App\Modules\MobilePwa\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class MobilePwaController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'mobile_pwa',
            'status' => 'enabled',
            'manifest' => '/manifest.webmanifest',
            'service_worker' => '/service-worker.js',
        ]);
    }

    public function sync(Request $request): JsonResponse
    {
        $data = $request->validate([
            'client_uuid' => ['required', 'string', 'max:64'],
            'events' => ['nullable', 'array'],
        ]);

        $events = $data['events'] ?? [];

        return response()->json([
            'module' => 'mobile_pwa',
            'acknowledged' => true,
            'client_uuid' => $data['client_uuid'],
            'accepted' => count($events),
        ]);
    }

    public function manifest(): BinaryFileResponse|Response
    {
        $path = public_path('manifest.webmanifest');

        if (! is_file($path)) {
            return response('{}', 404, ['Content-Type' => 'application/manifest+json']);
        }

        return response()->file($path, [
            'Content-Type' => 'application/manifest+json',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    public function serviceWorker(): BinaryFileResponse|Response
    {
        $path = public_path('service-worker.js');

        if (! is_file($path)) {
            return response('/* missing */', 404, ['Content-Type' => 'application/javascript']);
        }

        return response()->file($path, [
            'Content-Type' => 'application/javascript',
            'Cache-Control' => 'public, max-age=3600',
            'Service-Worker-Allowed' => '/',
        ]);
    }
}
