<?php

declare(strict_types=1);

namespace App\Modules\CheckpointsTags\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\CheckpointsTags\Filament\Resources\CheckpointResource\Pages;
use App\Modules\CheckpointsTags\Models\Checkpoint;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class CheckpointResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = Checkpoint::class;

    protected static ?string $navigationIcon = 'heroicon-o-qr-code';

    protected static ?string $navigationLabel = 'Checkpoints';

    protected static ?string $modelLabel = 'Checkpoint';

    protected static ?string $slug = 'checkpoints';

    public static function moduleId(): string
    {
        return 'checkpoints';
    }

    public static function getNavigationGroup(): string
    {
        return 'Patrols';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('site_id')
                ->relationship('site', 'name')
                ->searchable()
                ->required(),
            Forms\Components\Select::make('site_zone_id')
                ->relationship('zone', 'name')
                ->searchable(),
            Forms\Components\TextInput::make('name')->required()->maxLength(255),
            Forms\Components\TextInput::make('code')->maxLength(64),
            Forms\Components\Select::make('verification_type')
                ->options([
                    'qr' => 'QR',
                    'nfc' => 'NFC',
                    'gps' => 'GPS',
                    'ble' => 'BLE',
                ])
                ->required()
                ->native(false),
            Forms\Components\TextInput::make('latitude')->numeric(),
            Forms\Components\TextInput::make('longitude')->numeric(),
            Forms\Components\TextInput::make('radius_meters')->numeric()->default(50)->required(),
            Forms\Components\TextInput::make('nfc_uid')->maxLength(128),
            Forms\Components\TextInput::make('ble_uuid')->maxLength(128),
            Forms\Components\TextInput::make('qr_payload')->maxLength(255),
            Forms\Components\Toggle::make('requires_photo')->default(false),
            Forms\Components\Toggle::make('requires_notes')->default(false),
            Forms\Components\Textarea::make('hazard_prompt')->columnSpanFull(),
            Forms\Components\Toggle::make('is_active')->default(true),
            Forms\Components\Repeater::make('tags')
                ->relationship()
                ->schema([
                    Forms\Components\TextInput::make('tag_value')->required()->maxLength(255),
                    Forms\Components\Select::make('status')
                        ->options([
                            'active' => 'Active',
                            'damaged' => 'Damaged',
                            'missing' => 'Missing',
                        ])
                        ->default('active')
                        ->required(),
                    Forms\Components\Textarea::make('notes'),
                ])
                ->columnSpanFull()
                ->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('code')->toggleable(),
                Tables\Columns\TextColumn::make('site.name')->label('Site')->sortable(),
                Tables\Columns\TextColumn::make('verification_type')->badge(),
                Tables\Columns\TextColumn::make('radius_meters')->label('Radius (m)'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
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
            'index' => Pages\ListCheckpoints::route('/'),
            'create' => Pages\CreateCheckpoint::route('/create'),
            'edit' => Pages\EditCheckpoint::route('/{record}/edit'),
        ];
    }
}
