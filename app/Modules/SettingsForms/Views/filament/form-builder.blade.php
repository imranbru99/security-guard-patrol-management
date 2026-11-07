<x-filament-panels::page>
    <div class="space-y-4">
        <p class="text-sm text-gray-600 dark:text-gray-300">
            Simple field listing. Drag-and-drop builder arrives in a later iteration.
        </p>

        @forelse ($this->fields as $field)
            <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4 flex items-center justify-between gap-4">
                <div>
                    <div class="font-medium">{{ $field->label }}</div>
                    <div class="text-xs text-gray-500">
                        {{ $field->entity_type }} · {{ $field->key }} · {{ $field->type }}
                        @if ($field->required)
                            · required
                        @endif
                    </div>
                </div>
                <span class="text-xs uppercase tracking-wide text-gray-400">#{{ $field->sort_order }}</span>
            </div>
        @empty
            <p class="text-sm text-gray-500">No custom fields yet. Create some under Custom Fields.</p>
        @endforelse
    </div>
</x-filament-panels::page>
