<?php

declare(strict_types=1);

namespace App\Modules\ClientsSites\Filament\Resources\SiteResource\Pages;

use App\Modules\ClientsSites\Filament\Resources\SiteResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateSite extends CreateRecord
{
    protected static string $resource = SiteResource::class;
}
