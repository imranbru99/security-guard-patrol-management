<x-filament-panels::page>
    <div class="space-y-4">
        <p class="text-sm text-gray-600 dark:text-gray-300">
            Supported locales from <code>config('guardops.locales')</code>.
            AI-assisted translation sync will be added later via ai-hub.
        </p>

        <ul class="divide-y divide-gray-200 dark:divide-gray-700 rounded-lg border border-gray-200 dark:border-gray-700">
            @foreach ($this->locales as $locale)
                <li class="px-4 py-3 flex items-center justify-between">
                    <span class="font-mono text-sm">{{ $locale }}</span>
                    <span class="text-xs text-gray-400">configured</span>
                </li>
            @endforeach
        </ul>
    </div>
</x-filament-panels::page>
