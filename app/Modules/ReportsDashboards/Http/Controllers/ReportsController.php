<?php

declare(strict_types=1);

namespace App\Modules\ReportsDashboards\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ReportsController
{
    public function show(): JsonResponse
    {
        return response()->json([
            'module' => 'reports',
            'status' => 'enabled',
        ]);
    }

    public function exportCsv(): StreamedResponse
    {
        return response()->streamDownload(function (): void {
            $out = fopen('php://output', 'w');
            if ($out === false) {
                return;
            }
            fputcsv($out, ['metric', 'value']);
            fputcsv($out, ['stub', 1]);
            fclose($out);
        }, 'reports-export-stub.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
