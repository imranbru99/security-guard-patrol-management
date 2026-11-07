<?php

declare(strict_types=1);

namespace App\Modules\AttendanceTracking\Filament\Pages;

use App\Core\Concerns\HasModuleGate;
use Filament\Pages\Page;
use ImranDevBd\AttendanceHub\Support\DeviceCatalog;

final class DeviceManagerPage extends Page
{
    use HasModuleGate;

    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';

    protected static string $view = 'AttendanceTracking::filament.device-manager';

    protected static ?string $navigationLabel = 'Attendance Devices';

    protected static ?string $title = 'Attendance Device Manager';

    protected static ?string $slug = 'attendance-devices';

    /** @var list<string> */
    public array $providers = [];

    /** @var list<array{brand: string, models: int}> */
    public array $catalogSummary = [];

    public static function moduleId(): string
    {
        return 'attendance';
    }

    public static function getNavigationGroup(): string
    {
        return 'Scheduling';
    }

    public function mount(): void
    {
        $this->providers = [
            'zkteco', 'hikvision', 'suprema', 'dahua', 'adms',
            'webhook_bridge', 'virtual', 'fake',
        ];

        if (class_exists(DeviceCatalog::class)) {
            try {
                $all = DeviceCatalog::all();
                $byProvider = [];
                foreach ($all as $entry) {
                    $brand = (string) ($entry['provider'] ?? 'Unknown');
                    $byProvider[$brand] = ($byProvider[$brand] ?? 0) + 1;
                }
                foreach ($byProvider as $brand => $count) {
                    $this->catalogSummary[] = [
                        'brand' => $brand,
                        'models' => $count,
                    ];
                }
            } catch (\Throwable) {
                $this->catalogSummary = [];
            }
        }
    }
}
