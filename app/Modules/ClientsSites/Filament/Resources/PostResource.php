<?php

declare(strict_types=1);

namespace App\Modules\ClientsSites\Filament\Resources;

use App\Core\Concerns\HasModuleGate;
use App\Modules\ClientsSites\Filament\Resources\PostResource\Pages;
use App\Modules\ClientsSites\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class PostResource extends Resource
{
    use HasModuleGate;

    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationLabel = 'Posts';

    protected static ?string $modelLabel = 'Post';

    protected static ?string $slug = 'posts';

    public static function moduleId(): string
    {
        return 'clients_sites';
    }

    public static function getNavigationGroup(): string
    {
        return 'Clients';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('site_id')
                ->relationship('site', 'name')
                ->required()
                ->searchable()
                ->live(),
            Forms\Components\Select::make('site_zone_id')
                ->relationship(
                    'zone',
                    'name',
                    fn ($query, Forms\Get $get) => $query->when(
                        $get('site_id'),
                        fn ($q, $siteId) => $q->where('site_id', $siteId)
                    )
                )
                ->searchable()
                ->nullable(),
            Forms\Components\TextInput::make('name')->required()->maxLength(255),
            Forms\Components\TextInput::make('code')->maxLength(64),
            Forms\Components\Toggle::make('is_active')->default(true),
            Forms\Components\Repeater::make('requirements')
                ->relationship()
                ->schema([
                    Forms\Components\TextInput::make('role_label')->maxLength(128),
                    Forms\Components\TextInput::make('guards_required')->numeric()->default(1)->required(),
                    Forms\Components\TextInput::make('hours_label')->maxLength(128),
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
                Tables\Columns\TextColumn::make('zone.name')->label('Zone'),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
