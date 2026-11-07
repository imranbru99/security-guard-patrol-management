<?php

declare(strict_types=1);

namespace App\Modules\AiAssistants\Filament\Pages;

use App\Core\Concerns\HasModuleGate;
use App\Modules\AiAssistants\Models\AiDraft;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

final class AiAssistantsPage extends Page
{
    use HasModuleGate;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static string $view = 'AiAssistants::filament.ai-assistants';

    protected static ?string $navigationLabel = 'AI Assistants';

    protected static ?string $title = 'AI Assistants';

    protected static ?string $slug = 'ai-assistants';

    public static function moduleId(): string
    {
        return 'ai_assistants';
    }

    public static function getNavigationGroup(): string
    {
        return 'Commerce';
    }

    /** @return Collection<int, AiDraft> */
    public function getRecentDraftsProperty(): Collection
    {
        return AiDraft::query()->orderByDesc('created_at')->limit(25)->get();
    }
}
