<x-filament-panels::page>
    <div class="space-y-8">
        <p class="text-sm text-gray-600 dark:text-gray-300">
            Privacy: tracking sessions only accept pings inside <code>shift_started_at</code> …
            <code>shift_ends_at</code>. POST <code>/api/v1/tracking/ping</code>.
        </p>

        <section class="space-y-3">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Active sessions</h2>
            <ul class="divide-y divide-gray-200 dark:divide-gray-700 rounded-lg border border-gray-200 dark:border-gray-700">
                @forelse ($this->activeSessions as $session)
                    <li class="px-4 py-3">
                        <div class="font-medium">{{ $session->guardEmployee?->name ?? 'Guard' }}</div>
                        <div class="text-sm text-gray-500">
                            {{ $session->shift_started_at?->toDateTimeString() }}
                            → {{ $session->shift_ends_at?->toDateTimeString() }}
                            · {{ $session->ulid }}
                        </div>
                    </li>
                @empty
                    <li class="px-4 py-6 text-sm text-gray-500">No active tracking sessions.</li>
                @endforelse
            </ul>
        </section>

        <section class="space-y-3">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent pings</h2>
            <ul class="divide-y divide-gray-200 dark:divide-gray-700 rounded-lg border border-gray-200 dark:border-gray-700">
                @forelse ($this->recentPings as $ping)
                    <li class="px-4 py-3 text-sm">
                        {{ $ping->guardEmployee?->name ?? 'Guard' }}
                        · {{ $ping->latitude }}, {{ $ping->longitude }}
                        · {{ $ping->recorded_at?->toDateTimeString() }}
                    </li>
                @empty
                    <li class="px-4 py-6 text-sm text-gray-500">No location pings yet.</li>
                @endforelse
            </ul>
        </section>
    </div>
</x-filament-panels::page>
