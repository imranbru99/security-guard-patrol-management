<x-filament-panels::page>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($this->kpis as $key => $value)
            <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                <div class="text-xs uppercase tracking-wide text-gray-500">{{ str_replace('_', ' ', $key) }}</div>
                <div class="mt-2 text-2xl font-semibold">{{ $value }}</div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>