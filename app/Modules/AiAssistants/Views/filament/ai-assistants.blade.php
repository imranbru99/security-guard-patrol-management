<x-filament-panels::page>
    <div class="space-y-6">
        <p class="text-sm text-gray-600 dark:text-gray-300">
            {{ __('AiAssistants::messages.drafts_only') }}
            Budget tag: <code>tenant:{id}:ai_assistants</code>
        </p>
        <section class="space-y-3">
            <h2 class="text-lg font-semibold">Recent drafts</h2>
            <ul class="divide-y divide-gray-200 dark:divide-gray-700 rounded-lg border border-gray-200 dark:divide-gray-700">
                @forelse ($this->recentDrafts as $draft)
                    <li class="px-4 py-3 text-sm">
                        <div class="font-medium">{{ $draft->feature }} · {{ $draft->status }}</div>
                        <div class="text-gray-500">{{ $draft->ulid }} · {{ $draft->job_tag }}</div>
                    </li>
                @empty
                    <li class="px-4 py-6 text-sm text-gray-500">No AI drafts yet.</li>
                @endforelse
            </ul>
        </section>
    </div>
</x-filament-panels::page>