<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Core\Modules\ManifestRegistry;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

final class SeoController extends Controller
{
    public function robots(): Response
    {
        $body = implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /app',
            'Disallow: /guard',
            'Disallow: /client',
            'Disallow: /control',
            'Disallow: /admin',
            'Sitemap: '.url('/sitemap.xml'),
            'LLMs: '.url('/llms.txt'),
            '',
        ]);

        return response($body, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function llms(ManifestRegistry $registry): Response
    {
        $lines = [
            '# GuardOps',
            '',
            '> Security Guard and Patrol Management: shifts, checkpoints, incidents.',
            '',
            '## Product',
            '- Home: '.url('/'),
            '- Features: '.url('/features'),
            '- Pricing: '.url('/pricing'),
            '- Demo: '.url('/demo'),
            '- API health: '.url('/api/v1/health'),
            '',
            '## Modules',
        ];

        foreach ($registry->all() as $manifest) {
            if (($manifest['id'] ?? '') === 'demo_toggle') {
                continue;
            }
            $slug = is_array($manifest['public_page'] ?? null)
                ? (string) ($manifest['public_page']['slug'] ?? $manifest['id'])
                : (string) $manifest['id'];
            $name = is_array($manifest['name'] ?? null)
                ? (string) ($manifest['name']['en'] ?? $manifest['id'])
                : (string) ($manifest['name'] ?? $manifest['id']);
            $lines[] = "- {$name}: ".url('/features/'.$slug);
        }

        $lines[] = '';
        $lines[] = '## Catalog feed';
        $lines[] = '- /ai-catalog/llms.txt (when ai-catalog studio is enabled for platform admins)';
        $lines[] = '';

        return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function sitemap(ManifestRegistry $registry): Response
    {
        $urls = [
            '/',
            '/features',
            '/pricing',
            '/demo',
            '/solutions',
            '/about',
            '/contact',
            '/security',
            '/ai',
            '/mobile',
            '/integrations',
            '/api-docs',
            '/compare',
            '/tour',
            '/changelog',
            '/roadmap',
            '/status',
            '/careers',
            '/help',
            '/blog',
            '/case-studies',
            '/privacy',
            '/terms',
            '/cookies',
            '/dpa',
            '/biometric-consent',
            '/acceptable-use',
            '/quote',
            '/llms.txt',
        ];

        foreach (array_keys(config('public_site.solutions', [])) as $slug) {
            $urls[] = '/solutions/'.$slug;
        }

        foreach ($registry->all() as $manifest) {
            if (($manifest['id'] ?? '') === 'demo_toggle') {
                continue;
            }
            $slug = is_array($manifest['public_page'] ?? null)
                ? (string) ($manifest['public_page']['slug'] ?? '')
                : '';
            if ($slug !== '') {
                $urls[] = '/features/'.$slug;
            }
        }

        $xml = ['<?xml version="1.0" encoding="UTF-8"?>', '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'];
        foreach (array_unique($urls) as $path) {
            $xml[] = '  <url><loc>'.e(url($path)).'</loc></url>';
        }
        $xml[] = '</urlset>';

        return response(implode("\n", $xml), 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function rss(): Response
    {
        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
  <channel>
    <title>GuardOps Resources</title>
    <link>{$this->abs('/blog')}</link>
    <description>Demo content feed — articles appear when published.</description>
    <item>
      <title>Demo content: product overview</title>
      <link>{$this->abs('/blog')}</link>
      <description>Placeholder item labelled as demo content.</description>
    </item>
  </channel>
</rss>
XML;

        return response($xml, 200, ['Content-Type' => 'application/rss+xml; charset=UTF-8']);
    }

    private function abs(string $path): string
    {
        return url($path);
    }
}
