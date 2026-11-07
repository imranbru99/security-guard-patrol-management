<x-filament-panels::page>
    <div class="space-y-4">
        <p class="text-sm text-gray-600 dark:text-gray-300">
            Queued alerts awaiting acknowledge / dispatch (SOS, missed patrol, geofence, alarm, late clock-in).
        </p>
        <ul class="divide-y rounded-lg border">
            @forelse ($this->queuedAlerts as $alert)
                <li class="px-4 py-3 text-sm">
                    <div class="font-medium">{{ $alert->alert_type }} · {{ $alert->priority }}</div>
                    <div class="text-gray-500">
                        {{ $alert->summary ?: '—' }}
                        · {{ $alert->site?->name ?? 'No site' }}
                        · {{ $alert->ulid }}
                    </div>
                </li>
            @empty
                <li class="px-4 py-6 text-sm text-gray-500">Alert queue is empty.</li>
            @endforelse
        </ul>
    </div>
</x-filament-panels::page>
