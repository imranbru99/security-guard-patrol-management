<x-filament-panels::page>
    <div class="space-y-8">
        <section class="space-y-3">
            <h2 class="text-lg font-semibold">Open SOS</h2>
            <ul class="divide-y rounded-lg border">
                @forelse ($this->openSos as $sos)
                    <li class="px-4 py-3 text-sm">
                        {{ $sos->kind }} · {{ $sos->guardEmployee?->name ?? 'Guard' }}
                        · {{ $sos->triggered_at?->toDateTimeString() }}
                    </li>
                @empty
                    <li class="px-4 py-6 text-sm text-gray-500">No open SOS.</li>
                @endforelse
            </ul>
        </section>
        <section class="space-y-3">
            <h2 class="text-lg font-semibold">Active dispatch jobs</h2>
            <ul class="divide-y rounded-lg border">
                @forelse ($this->activeJobs as $job)
                    <li class="px-4 py-3 text-sm">
                        <span class="font-medium">{{ $job->alert_type }}</span>
                        · {{ $job->priority }} · {{ $job->status }}
                        · {{ $job->site?->name ?? '—' }}
                        · {{ $job->assignedGuardEmployee?->name ?? 'Unassigned' }}
                    </li>
                @empty
                    <li class="px-4 py-6 text-sm text-gray-500">No active dispatch jobs.</li>
                @endforelse
            </ul>
        </section>
    </div>
</x-filament-panels::page>
