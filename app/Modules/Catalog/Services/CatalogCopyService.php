<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Services;

use ImranDevBd\AiHub\Facades\AIHub;

/**
 * Demo service that returns canned catalog listing copy via AIHub::fake().
 * Maps conceptually to ImranDevBd\AiCatalog listing generation without calling e-commerce paths.
 */
final class CatalogCopyService
{
    /**
     * @return array{title: string, summary: string, body: string, meta_description: string, source: string}
     */
    public function generateListing(string $keyword, string $locale = 'en'): array
    {
        $canned = json_encode([
            'title' => 'Professional Guard Package — '.$keyword,
            'summary' => 'Trained security officers for '.$keyword.' with patrol and incident coverage.',
            'body' => 'Our '.$keyword.' package includes licensed guards, shift handover, and incident logging.',
            'meta_description' => 'Hire '.$keyword.' security services with GuardOps catalog listings.',
        ], JSON_THROW_ON_ERROR);

        if (class_exists(AIHub::class) && method_exists(AIHub::class, 'fake')) {
            AIHub::fake($canned);

            $response = AIHub::prompt('Generate catalog listing for: '.$keyword)
                ->forJob('tenant:demo:catalog_copy')
                ->send();

            $decoded = $response->json();
            if (is_array($decoded)) {
                return [
                    'title' => (string) ($decoded['title'] ?? 'Catalog item'),
                    'summary' => (string) ($decoded['summary'] ?? ''),
                    'body' => (string) ($decoded['body'] ?? ''),
                    'meta_description' => (string) ($decoded['meta_description'] ?? ''),
                    'source' => 'aihub_fake',
                ];
            }
        }

        return [
            'title' => 'Professional Guard Package — '.$keyword,
            'summary' => 'Trained security officers for '.$keyword.' with patrol and incident coverage.',
            'body' => 'Our '.$keyword.' package includes licensed guards, shift handover, and incident logging.',
            'meta_description' => 'Hire '.$keyword.' security services with GuardOps catalog listings.',
            'source' => 'canned',
        ];
    }
}
