<x-filament-panels::page>
    <div class="space-y-6">
        <p class="text-sm text-gray-600 dark:text-gray-300">
            Device management is provided by
            <code>imrandevbd/laravel-attendance-hub</code>.
            Register terminals, sync punches, and use
            <code>AttendanceHub::fake()</code> / <code>attendance:simulate</code> for demos without hardware.
            Schedule <code>attendance:sync</code> via this module's scheduled jobs.
        </p>

        <div>
            <h3 class="text-sm font-semibold mb-2">Supported providers</h3>
            <ul class="flex flex-wrap gap-2 text-xs">
                @foreach ($this->providers as $provider)
                    <li class="rounded border border-gray-200 dark:border-gray-700 px-2 py-1 font-mono">{{ $provider }}</li>
                @endforeach
            </ul>
        </div>

        <div>
            <h3 class="text-sm font-semibold mb-2">Hub concepts</h3>
            <ul class="list-disc list-inside text-sm text-gray-700 dark:text-gray-200 space-y-1">
                <li><strong>attendance_devices</strong> — hardware terminals &amp; credentials</li>
                <li><strong>attendance_logs</strong> — normalized punches from any driver</li>
                <li><strong>device_user_maps</strong> — badge/PIN ↔ employee_id</li>
                <li><strong>VirtualDriver</strong> — GPS / QR / selfie check-in channel</li>
                <li><strong>AttendanceCalculator</strong> — late / OT / early-out / absent</li>
            </ul>
        </div>

        @if (count($this->catalogSummary) > 0)
            <div>
                <h3 class="text-sm font-semibold mb-2">Catalog snapshot</h3>
                <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($this->catalogSummary as $row)
                        <div class="rounded border border-gray-200 dark:border-gray-700 px-3 py-2 text-sm">
                            <div class="font-medium">{{ $row['brand'] }}</div>
                            <div class="text-xs text-gray-500">{{ $row['models'] }} model entries</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
