<x-filament-panels::page>
    <div class="space-y-4">
        <p class="text-sm text-gray-600 dark:text-gray-300">
            Active patrol tours. Submit scans via <code>POST /api/v1/patrol/scan</code> with a unique
            <code>client_uuid</code> for offline-safe sync.
        </p>

        <ul class="divide-y divide-gray-200 dark:divide-gray-700 rounded-lg border border-gray-200 dark:border-gray-700">
            @forelse ($this->activeTours as $tour)
                <li class="px-4 py-3">
                    <div class="font-medium text-gray-900 dark:text-gray-100">
                        {{ $tour->patrolRoute?->name ?? 'Route' }}
                    </div>
                    <div class="text-sm text-gray-500">
                        Tour {{ $tour->ulid }}
                        · Guard {{ $tour->guardEmployee?->name ?? '—' }}
                        · Started {{ $tour->started_at?->toDateTimeString() ?? '—' }}
                    </div>
                </li>
            @empty
                <li class="px-4 py-6 text-sm text-gray-500">No active tours right now.</li>
            @endforelse
        </ul>
    </div>
</x-filament-panels::page>
