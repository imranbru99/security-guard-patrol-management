<x-filament-panels::page>
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($modules as $module)
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-4 space-y-3">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ $module['name'] }}</h3>
                        <p class="text-xs text-gray-500">{{ $module['group'] }} · <code>{{ $module['key'] }}</code></p>
                    </div>
                    @if ($module['is_core'])
                        <span class="text-xs font-medium text-teal-700 dark:text-teal-300">Core</span>
                    @elseif ($module['enabled'])
                        <span class="text-xs font-medium text-green-700 dark:text-green-300">On</span>
                    @else
                        <span class="text-xs font-medium text-gray-500">Off</span>
                    @endif
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3">{{ $module['description'] }}</p>
                <x-filament::button
                    wire:click="toggle('{{ $module['key'] }}')"
                    size="sm"
                    color="{{ $module['enabled'] && ! $module['is_core'] ? 'danger' : 'primary' }}"
                    :disabled="$module['is_core']"
                >
                    @if ($module['is_core'])
                        Always on
                    @elseif ($module['enabled'])
                        Disable
                    @else
                        Enable
                    @endif
                </x-filament::button>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
