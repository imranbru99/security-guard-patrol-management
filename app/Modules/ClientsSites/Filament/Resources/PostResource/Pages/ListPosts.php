<?php

declare(strict_types=1);

namespace App\Modules\ClientsSites\Filament\Resources\PostResource\Pages;

use App\Modules\ClientsSites\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

final class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
