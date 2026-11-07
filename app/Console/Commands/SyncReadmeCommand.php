<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Core\Modules\ManifestRegistry;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

final class SyncReadmeCommand extends Command
{
    protected $signature = 'docs:sync-readme {--check : Fail if README feature matrix is out of sync}';

    protected $description = 'Regenerate the README feature matrix from module manifests';

    public function handle(ManifestRegistry $registry): int
    {
        $matrix = $this->buildMatrix($registry);
        $path = base_path('README.md');
        $readme = File::exists($path) ? File::get($path) : '';

        $start = '<!-- FEATURE_MATRIX:START -->';
        $end = '<!-- FEATURE_MATRIX:END -->';

        if (! str_contains($readme, $start) || ! str_contains($readme, $end)) {
            $this->error('README.md missing FEATURE_MATRIX markers.');

            return self::FAILURE;
        }

        $pattern = '/'.preg_quote($start, '/').'.*?'.preg_quote($end, '/').'/s';
        $replacement = $start."\n\n".$matrix."\n".$end;
        $updated = preg_replace($pattern, $replacement, $readme);
        if (! is_string($updated)) {
            $this->error('Failed to rewrite README matrix.');

            return self::FAILURE;
        }

        if ($this->option('check')) {
            if (trim($updated) !== trim($readme)) {
                $this->error('README feature matrix is out of sync. Run php artisan docs:sync-readme');

                return self::FAILURE;
            }
            $this->info('README feature matrix is in sync.');

            return self::SUCCESS;
        }

        File::put($path, $updated);
        $this->info('README feature matrix updated.');

        return self::SUCCESS;
    }

    private function buildMatrix(ManifestRegistry $registry): string
    {
        $lines = [
            '| Module ID | Name | Group | Tier | Features |',
            '|-----------|------|-------|------|----------|',
        ];

        foreach ($registry->all() as $manifest) {
            if (($manifest['id'] ?? '') === 'demo_toggle') {
                continue;
            }
            $name = is_array($manifest['name'] ?? null)
                ? (string) ($manifest['name']['en'] ?? $manifest['id'])
                : (string) ($manifest['name'] ?? $manifest['id']);
            $features = implode('; ', array_slice($manifest['features'] ?? [], 0, 3));
            $lines[] = sprintf(
                '| `%s` | %s | %s | %s | %s |',
                $manifest['id'],
                str_replace('|', '/', $name),
                $manifest['group'] ?? '',
                $manifest['plan_tier'] ?? '',
                str_replace('|', '/', $features),
            );
        }

        return implode("\n", $lines);
    }
}
