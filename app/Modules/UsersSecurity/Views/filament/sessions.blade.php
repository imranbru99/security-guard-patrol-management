<x-filament-panels::page>
    <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-2 text-left">User</th>
                    <th class="px-4 py-2 text-left">IP</th>
                    <th class="px-4 py-2 text-left">Last activity</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sessions as $session)
                    <tr class="border-t border-gray-100 dark:border-gray-700">
                        <td class="px-4 py-2">{{ $session['user_email'] ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $session['ip_address'] ?? '—' }}</td>
                        <td class="px-4 py-2">{{ \Illuminate\Support\Carbon::createFromTimestamp($session['last_activity'])->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-4 text-gray-500">No active sessions for this tenant.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament-panels::page>
