<?php

declare(strict_types=1);

namespace App\Modules\ClientsSites\Filament\Resources\PostResource\Pages;

use App\Modules\ClientsSites\Filament\Resources\PostResource;
use Filament\Resources\Pages\CreateRecord;

final class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;
}
