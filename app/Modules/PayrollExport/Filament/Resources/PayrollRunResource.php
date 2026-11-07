<?php

declare(strict_types=1);

namespace App\Modules\PayrollExport\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\PayrollExport\Filament\Resources\PayrollRunResource\Pages;
use App\Modules\PayrollExport\Models\PayrollLine;
use App\Modules\PayrollExport\Models\PayrollRun;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class PayrollRunResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = PayrollRun::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Payroll runs';

    protected static ?string $modelLabel = 'Payroll Run';

    protected static ?string $slug = 'payroll-runs';

    public static function moduleId(): string
    {
        return 'payroll_export';
    }

    public static function getNavigationGroup(): string
    {
        return 'People';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\DatePicker::make('period_start')->required(),
            Forms\Components\DatePicker::make('period_end')->required(),
            Forms\Components\Select::make('status')
                ->options([
                    'draft' => 'Draft',
                    'ready' => 'Ready',
                    'exported' => 'Exported',
                ])
                ->required()
                ->default('draft'),
            Forms\Components\Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('period_start')->date()->sortable(),
                Tables\Columns\TextColumn::make('period_end')->date()->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('lines_count')->counts('lines')->label('Lines'),
                Tables\Columns\TextColumn::make('exported_at')->dateTime()->toggleable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('exportCsv')
                    ->label('Export CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (PayrollRun $record): StreamedResponse {
                        $record->update([
                            'status' => 'exported',
                            'exported_at' => now(),
                        ]);

                        Notification::make()
                            ->title('CSV export stub ready (not statutory payroll)')
                            ->success()
                            ->send();

                        $filename = 'payroll-run-'.$record->ulid.'.csv';

                        return response()->streamDownload(function () use ($record): void {
                            $out = fopen('php://output', 'w');
                            fputcsv($out, ['guard_ulid', 'hours', 'base_amount', 'allowance_amount', 'total_amount']);
                            /** @var PayrollLine $line */
                            foreach ($record->lines()->with('guardEmployee')->get() as $line) {
                                fputcsv($out, [
                                    $line->guardEmployee?->ulid,
                                    $line->hours,
                                    $line->base_amount,
                                    $line->allowance_amount,
                                    $line->total_amount,
                                ]);
                            }
                            fclose($out);
                        }, $filename, ['Content-Type' => 'text/csv']);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayrollRuns::route('/'),
            'create' => Pages\CreatePayrollRun::route('/create'),
            'edit' => Pages\EditPayrollRun::route('/{record}/edit'),
        ];
    }
}
