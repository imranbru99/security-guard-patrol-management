<x-filament-panels::page>
    <div class="space-y-4">
        <p class="text-sm text-gray-600 dark:text-gray-300">
            ICS feed: <code>/feeds/{tenant-slug}/shifts.ics</code> · SMS via FakeSmsDriver
        </p>
        <ul class="divide-y divide-gray-200 dark:divide-gray-700 rounded-lg border border-gray-200 dark:border-gray-700">
            @forelse ($this->connections as $connection)
                <li class="px-4 py-3 text-sm">
                    <div class="font-medium">{{ $connection->name }}</div>
                    <div class="text-gray-500">{{ $connection->driver }} · {{ $connection->status }}</div>
                </li>
            @empty
                <li class="px-4 py-6 text-sm text-gray-500">No integration connections yet.</li>
            @endforelse
        </ul>
    </div>
</x-filament-panels::page>