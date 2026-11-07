<x-filament-panels::page>
    <div class="space-y-4">
        <p class="text-sm text-gray-600 dark:text-gray-300">
            Printable tag sheet stub for active checkpoints. Download the PDF from the header action.
        </p>

        <ul class="divide-y divide-gray-200 dark:divide-gray-700 rounded-lg border border-gray-200 dark:border-gray-700">
            @forelse ($this->checkpoints as $checkpoint)
                <li class="px-4 py-3 flex items-center justify-between gap-4">
                    <div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $checkpoint->name }}</div>
                        <div class="text-sm text-gray-500">
                            {{ strtoupper($checkpoint->verification_type) }}
                            · {{ $checkpoint->site?->name ?? 'No site' }}
                            @if ($checkpoint->code)
                                · {{ $checkpoint->code }}
                            @endif
                        </div>
                    </div>
                    <span class="text-xs uppercase tracking-wide text-teal-700 dark:text-teal-300">
                        {{ $checkpoint->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </li>
            @empty
                <li class="px-4 py-6 text-sm text-gray-500">No active checkpoints yet.</li>
            @endforelse
        </ul>
    </div>
</x-filament-panels::page>
