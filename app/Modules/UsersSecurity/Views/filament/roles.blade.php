<x-filament-panels::page>
    <form wire:submit="createRole" class="space-y-4 mb-8">
        {{ $this->form }}
        <x-filament::button type="submit">Create role</x-filament::button>
    </form>

    <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Scope</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($roles as $role)
                    <tr class="border-t border-gray-100 dark:border-gray-700">
                        <td class="px-4 py-2">{{ $role['name'] }}</td>
                        <td class="px-4 py-2">{{ $role['tenant_id'] ? 'Tenant' : 'Global' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-4 py-4 text-gray-500">No roles yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament-panels::page>
