<x-filament-panels::page>
    <div class="space-y-4">
        <p class="text-sm text-gray-600 dark:text-gray-300">
            Live site status for your organisation. Full guard-on-duty and patrol feeds land in later phases.
        </p>

        <ul class="divide-y divide-gray-200 dark:divide-gray-700 rounded-lg border border-gray-200 dark:border-gray-700">
            @forelse ($this->sites as $site)
                <li class="px-4 py-3 flex items-center justify-between gap-4">
                    <div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $site->name }}</div>
                        <div class="text-sm text-gray-500">{{ $site->address ?: 'No address on file' }}</div>
                    </div>
                    <span class="text-xs uppercase tracking-wide text-teal-700 dark:text-teal-300">Active</span>
                </li>
            @empty
                <li class="px-4 py-6 text-sm text-gray-500">No active sites available.</li>
            @endforelse
        </ul>
    </div>
</x-filament-panels::page>
