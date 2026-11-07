<x-filament-panels::page>
    <div class="space-y-6">
        <p class="text-sm text-gray-600 dark:text-gray-300">
            Coverage gaps predicted from historical shift counts.
        </p>
        <section class="space-y-3">
            <h2 class="text-lg font-semibold">Recent forecast runs</h2>
            <ul class="divide-y divide-gray-200 dark:divide-gray-700 rounded-lg border border-gray-200 dark:border-gray-700">
                @forelse ($this->recentRuns as $run)
                    <li class="px-4 py-3 text-sm">
                        <div class="font-medium">{{ $run->horizon }} · {{ $run->status }}</div>
                        <div class="text-gray-500">
                            gaps: {{ $run->summary['gap_count'] ?? 0 }}
                            · avg shifts/day: {{ $run->summary['average_daily_shifts'] ?? 0 }}
                        </div>
                    </li>
                @empty
                    <li class="px-4 py-6 text-sm text-gray-500">No forecast runs yet.</li>
                @endforelse
            </ul>
        </section>
    </div>
</x-filament-panels::page>