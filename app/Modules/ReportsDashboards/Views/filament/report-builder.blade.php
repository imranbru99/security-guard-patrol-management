<x-filament-panels::page>
    <div class="space-y-4">
        <p class="text-sm text-gray-600 dark:text-gray-300">
            Report builder shell. Filters, charts, and scheduled email delivery land in a later pass.
            Use <strong>Export CSV (stub)</strong> for a placeholder download.
        </p>

        <h3 class="text-sm font-semibold">Saved reports</h3>
        @forelse ($this->savedReports as $report)
            <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-3 flex justify-between gap-3">
                <div>
                    <div class="font-medium">{{ $report->name }}</div>
                    <div class="text-xs text-gray-500">{{ $report->type }}</div>
                </div>
                <span class="text-xs text-gray-400">{{ $report->is_active ? 'active' : 'inactive' }}</span>
            </div>
        @empty
            <p class="text-sm text-gray-500">No saved reports yet.</p>
        @endforelse
    </div>
</x-filament-panels::page>
