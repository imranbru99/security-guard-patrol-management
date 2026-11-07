<?php

declare(strict_types=1);

namespace App\Modules\AuditCompliance\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\AuditCompliance\Filament\Resources\AuditEventResource\Pages;
use App\Modules\AuditCompliance\Models\AuditEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

final class AuditEventResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = AuditEvent::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Audit events';

    protected static ?string $modelLabel = 'Audit event';

    protected static ?string $slug = 'audit-events';

    public static function moduleId(): string
    {
        return 'audit';
    }

    public static function getNavigationGroup(): string
    {
        return 'Compliance';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(?Model $record): bool
    {
        return false;
    }

    public static function canDelete(?Model $record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('action')->disabled(),
            Forms\Components\TextInput::make('subject_type')->disabled(),
            Forms\Components\TextInput::make('subject_id')->disabled(),
            Forms\Components\Textarea::make('hash')->disabled()->columnSpanFull(),
            Forms\Components\Textarea::make('prev_hash')->disabled()->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('action')->searchable(),
                Tables\Columns\TextColumn::make('actor.email')->label('Actor'),
                Tables\Columns\TextColumn::make('subject_type')->toggleable(),
                Tables\Columns\TextColumn::make('ip')->toggleable(),
                Tables\Columns\TextColumn::make('hash')->limit(12)->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuditEvents::route('/'),
            'view' => Pages\ViewAuditEvent::route('/{record}'),
        ];
    }
}
