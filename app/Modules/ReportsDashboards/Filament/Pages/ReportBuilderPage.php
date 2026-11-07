<?php

declare(strict_types=1);

namespace App\Modules\ReportsDashboards\Filament\Pages;

use App\Core\Concerns\HasModuleGate;
use App\Modules\ReportsDashboards\Models\SavedReport;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ReportBuilderPage extends Page
{
    use HasModuleGate;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'ReportsDashboards::filament.report-builder';

    protected static ?string $navigationLabel = 'Report Builder';

    protected static ?string $title = 'Report Builder';

    protected static ?string $slug = 'report-builder';

    public static function moduleId(): string
    {
        return 'reports';
    }

    public static function getNavigationGroup(): string
    {
        return 'Reports';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportCsv')
                ->label('Export CSV (stub)')
                ->action(fn (): StreamedResponse => $this->exportCsvStub()),
        ];
    }

    /**
     * @return Collection<int, SavedReport>
     */
    public function getSavedReportsProperty(): Collection
    {
        return SavedReport::query()->orderByDesc('updated_at')->limit(25)->get();
    }

    public function exportCsvStub(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="report-stub.csv"',
        ];

        return response()->streamDownload(function (): void {
            $out = fopen('php://output', 'w');
            if ($out === false) {
                return;
            }
            fputcsv($out, ['metric', 'value']);
            fputcsv($out, ['patrols_completed', 0]);
            fputcsv($out, ['incidents_open', 0]);
            fputcsv($out, ['note', 'CSV export stub — wire real queries later']);
            fclose($out);
        }, 'report-stub.csv', $headers);
    }
}
