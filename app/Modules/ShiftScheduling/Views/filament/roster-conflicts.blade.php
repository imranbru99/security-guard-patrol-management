<x-filament-panels::page>
    <div class="space-y-4">
        <p class="text-sm text-gray-600 dark:text-gray-300">
            Dry-run of <code>RosterConflictEngine</code> against current shift assignments
            (overlap = hard, rest period &lt; {{ $this->minRestHours }}h).
        </p>

        <div class="text-sm">
            <strong>{{ count($this->violations) }}</strong> violation(s) found.
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800 text-left">
                    <tr>
                        <th class="px-3 py-2">Severity</th>
                        <th class="px-3 py-2">Code</th>
                        <th class="px-3 py-2">Guard</th>
                        <th class="px-3 py-2">Assignments</th>
                        <th class="px-3 py-2">Message</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->violations as $row)
                        <tr class="border-t border-gray-100 dark:border-gray-700">
                            <td class="px-3 py-2">
                                <span @class([
                                    'font-medium',
                                    'text-red-600' => ($row['severity'] ?? '') === 'hard',
                                    'text-amber-600' => ($row['severity'] ?? '') === 'soft',
                                ])>{{ $row['severity'] ?? '—' }}</span>
                            </td>
                            <td class="px-3 py-2 font-mono text-xs">{{ $row['code'] ?? '—' }}</td>
                            <td class="px-3 py-2">#{{ $row['guard_id'] ?? '—' }}</td>
                            <td class="px-3 py-2 text-xs">
                                {{ $row['assignment_id_a'] ?? '—' }} / {{ $row['assignment_id_b'] ?? '—' }}
                            </td>
                            <td class="px-3 py-2">{{ $row['message'] ?? '' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3 py-6 text-center text-gray-500">
                                No conflicts detected in the current roster assignments.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
