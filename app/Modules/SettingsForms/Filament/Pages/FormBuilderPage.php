<?php

declare(strict_types=1);

namespace App\Modules\SettingsForms\Filament\Pages;

use App\Core\Concerns\HasModuleGate;
use App\Modules\SettingsForms\Models\CustomField;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

final class FormBuilderPage extends Page
{
    use HasModuleGate;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static string $view = 'SettingsForms::filament.form-builder';

    protected static ?string $navigationLabel = 'Form Builder';

    protected static ?string $title = 'Form Builder';

    protected static ?string $slug = 'form-builder';

    public static function moduleId(): string
    {
        return 'settings_forms';
    }

    public static function getNavigationGroup(): string
    {
        return 'Settings';
    }

    /**
     * @return Collection<int, CustomField>
     */
    public function getFieldsProperty(): Collection
    {
        return CustomField::query()->orderBy('entity_type')->orderBy('sort_order')->get();
    }
}
