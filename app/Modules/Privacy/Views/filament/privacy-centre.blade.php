<x-filament-panels::page>
    <div class="grid gap-6 lg:grid-cols-3">
        <section>
            <h2 class="font-semibold mb-2">Consents</h2>
            <p class="text-2xl">{{ $this->consentCount }}</p>
        </section>
        <section>
            <h2 class="font-semibold mb-2">Retention policies</h2>
            <p class="text-2xl">{{ $this->policyCount }}</p>
        </section>
        <section>
            <h2 class="font-semibold mb-2">DSAR requests</h2>
            <p class="text-2xl">{{ $this->dsarCount }}</p>
        </section>
    </div>
    <p class="mt-6 text-sm text-gray-500">ErasureJob stub processes DSAR erasure requests without destructive deletes yet.</p>
</x-filament-panels::page>