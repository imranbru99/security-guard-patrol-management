<?php

declare(strict_types=1);

namespace App\Modules\CheckpointsTags\Filament\Pages;

use App\Core\Concerns\HasModuleGate;
use App\Modules\CheckpointsTags\Models\Checkpoint;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use ImranDev\UnicodePdf\Facades\UnicodePdf;
use Symfony\Component\HttpFoundation\Response;

final class CheckpointTagSheetsPage extends Page
{
    use HasModuleGate;

    protected static ?string $navigationIcon = 'heroicon-o-printer';

    protected static string $view = 'CheckpointsTags::filament.tag-sheets';

    protected static ?string $navigationLabel = 'Tag sheets';

    protected static ?string $title = 'Checkpoint tag sheets';

    protected static ?string $slug = 'checkpoint-tag-sheets';

    public static function moduleId(): string
    {
        return 'checkpoints';
    }

    public static function getNavigationGroup(): string
    {
        return 'Patrols';
    }

    /**
     * @return Collection<int, Checkpoint>
     */
    public function getCheckpointsProperty(): Collection
    {
        return Checkpoint::query()
            ->with('site')
            ->where('is_active', true)
            ->orderBy('name')
            ->limit(100)
            ->get();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadPdf')
                ->label('Download tag sheet (PDF stub)')
                ->action(fn (): Response => $this->downloadStubPdf()),
        ];
    }

    public function downloadStubPdf(): Response
    {
        $rows = $this->checkpoints->map(function (Checkpoint $checkpoint): string {
            $siteName = $checkpoint->relationLoaded('site') && $checkpoint->site !== null
                ? $checkpoint->site->name
                : '—';

            return '<tr><td>'.e($checkpoint->name).'</td><td>'.e((string) $checkpoint->code).'</td>'
                .'<td>'.e($checkpoint->verification_type).'</td><td>'.e($siteName).'</td></tr>';
        })->implode('');

        $html = '<h1>Checkpoint tag sheet</h1>'
            .'<p>Stub PDF — print QR/NFC labels from this registry.</p>'
            .'<table border="1" cellpadding="6" cellspacing="0" width="100%">'
            .'<thead><tr><th>Name</th><th>Code</th><th>Type</th><th>Site</th></tr></thead>'
            .'<tbody>'.$rows.'</tbody></table>';

        return UnicodePdf::loadHtml($html)->download('checkpoint-tag-sheet.pdf');
    }
}
