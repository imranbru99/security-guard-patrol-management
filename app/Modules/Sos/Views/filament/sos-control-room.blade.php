<x-filament-panels::page>
    <div class="space-y-4">
        <p class="text-sm text-gray-600 dark:text-gray-300">
            Open and acknowledged SOS events. Trigger via <code>POST /api/v1/sos/trigger</code>.
        </p>
        <ul class="divide-y divide-gray-200 dark:divide-gray-700 rounded-lg border border-gray-200 dark:divide-gray-700">
            @forelse ($this->openEvents as $event)
                <li class="px-4 py-3">
                    <div class="font-medium">
                        {{ strtoupper($event->kind) }}
                        @if ($event->silent) · silent @endif
                        @if ($event->drill_mode) · drill @endif
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $event->guardEmployee?->name ?? 'Unknown guard' }}
                        · {{ $event->site?->name ?? 'No site' }}
                        · {{ $event->status }}
                        · {{ $event->triggered_at?->toDateTimeString() }}
                    </div>
                </li>
            @empty
                <li class="px-4 py-6 text-sm text-gray-500">No open SOS events.</li>
            @endforelse
        </ul>
    </div>
</x-filament-panels::page>
