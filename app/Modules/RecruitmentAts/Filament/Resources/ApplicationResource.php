<?php

declare(strict_types=1);

namespace App\Modules\RecruitmentAts\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\RecruitmentAts\Filament\Resources\ApplicationResource\Pages;
use App\Modules\RecruitmentAts\Models\Application;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class ApplicationResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = Application::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Applications';

    protected static ?string $modelLabel = 'Application';

    protected static ?string $slug = 'applications';

    public static function moduleId(): string
    {
        return 'recruitment';
    }

    public static function getNavigationGroup(): string
    {
        return 'People';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('applicant_id')
                ->relationship('applicant', 'full_name')
                ->searchable()
                ->required()
                ->createOptionForm([
                    Forms\Components\TextInput::make('full_name')->required()->maxLength(255),
                    Forms\Components\TextInput::make('email')->email()->maxLength(255),
                    Forms\Components\TextInput::make('phone')->maxLength(64),
                ]),
            Forms\Components\Select::make('pipeline_stage')
                ->options([
                    'applied' => 'Applied',
                    'screening' => 'Screening',
                    'interview' => 'Interview',
                    'offer' => 'Offer',
                    'hired' => 'Hired',
                    'rejected' => 'Rejected',
                ])
                ->required()
                ->default('applied'),
            Forms\Components\TextInput::make('position')->maxLength(255),
            Forms\Components\DateTimePicker::make('applied_at')->default(now()),
            Forms\Components\Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('applicant.full_name')->label('Applicant')->searchable(),
                Tables\Columns\TextColumn::make('position')->searchable(),
                Tables\Columns\TextColumn::make('pipeline_stage')
                    ->badge()
                    ->sortable()
                    ->color(fn (string $state): string => match ($state) {
                        'hired' => 'success',
                        'rejected' => 'danger',
                        'offer' => 'warning',
                        'interview' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('applied_at')->dateTime()->sortable(),
            ])
            ->defaultSort('pipeline_stage')
            ->defaultGroup('pipeline_stage')
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApplications::route('/'),
            'create' => Pages\CreateApplication::route('/create'),
            'edit' => Pages\EditApplication::route('/{record}/edit'),
        ];
    }
}
