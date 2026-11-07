<x-filament-panels::page>
    <div class="grid gap-4 md:grid-cols-2">
        @foreach ($presets as $key => $preset)
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-4 space-y-3">
                <h3 class="font-semibold text-gray-900 dark:text-white">{{ $preset['label'] }}</h3>
                <p class="text-xs text-gray-500"><code>{{ $key }}</code></p>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    {{ $preset['modules'] === ['*'] ? 'All synced modules' : count($preset['modules']).' modules' }}
                </p>
                <x-filament::button wire:click="applyPreset('{{ $key }}')" size="sm">
                    Apply preset
                </x-filament::button>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
