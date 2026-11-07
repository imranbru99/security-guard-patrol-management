<x-filament-panels::page>
    <div class="space-y-6 max-w-lg">
        <p class="text-sm text-gray-600 dark:text-gray-300">
            Use SOS only for real emergencies unless drill mode is enabled. Control room receives the alert immediately.
        </p>

        <div class="space-y-3">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" wire:model="silent" class="rounded border-gray-300">
                Silent SOS (no audible alarm on device)
            </label>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" wire:model="drillMode" class="rounded border-gray-300">
                Drill mode
            </label>
        </div>

        <button
            type="button"
            wire:click="triggerSos"
            wire:confirm="Trigger SOS now?"
            class="w-full rounded-xl bg-rose-600 px-6 py-6 text-lg font-semibold text-white shadow hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500"
        >
            Hold / tap — Trigger SOS
        </button>
    </div>
</x-filament-panels::page>
