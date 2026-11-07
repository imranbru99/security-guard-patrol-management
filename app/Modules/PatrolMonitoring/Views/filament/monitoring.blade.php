<x-filament-panels::page>
    <div class="space-y-8">
        <section class="space-y-3">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Active tours</h2>
            <ul class="divide-y divide-gray-200 dark:divide-gray-700 rounded-lg border border-gray-200 dark:border-gray-700">
                @forelse ($this->activeTours as $tour)
                    <li class="px-4 py-3 flex items-center justify-between gap-4">
                        <div>
                            <div class="font-medium">{{ $tour->patrolRoute?->name ?? 'Route' }}</div>
                            <div class="text-sm text-gray-500">
                                {{ $tour->guardEmployee?->name ?? 'Guard' }}
                                · started {{ $tour->started_at?->toDateTimeString() ?? '—' }}
                            </div>
                        </div>
                        <span class="text-xs uppercase text-amber-700 dark:text-amber-300">{{ $tour->status }}</span>
                    </li>
                @empty
                    <li class="px-4 py-6 text-sm text-gray-500">No active tours.</li>
                @endforelse
            </ul>
        </section>

        <section class="space-y-3">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent patrol events</h2>
            <ul class="divide-y divide-gray-200 dark:divide-gray-700 rounded-lg border border-gray-200 dark:border-gray-700">
                @forelse ($this->recentEvents as $event)
                    <li class="px-4 py-3">
                        <div class="font-medium">{{ $event->type }}</div>
                        <div class="text-sm text-gray-500">
                            Checkpoint {{ $event->checkpoint?->name ?? $event->checkpoint_id }}
                            · {{ $event->occurred_at?->toDateTimeString() }}
                            · key {{ $event->idempotency_key }}
                        </div>
                    </li>
                @empty
                    <li class="px-4 py-6 text-sm text-gray-500">No patrol events yet.</li>
                @endforelse
            </ul>
        </section>
    </div>
</x-filament-panels::page>
