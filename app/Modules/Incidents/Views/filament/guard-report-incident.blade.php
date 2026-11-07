<x-filament-panels::page>
    <form wire:submit="submit" class="space-y-6 max-w-xl">
        {{ $this->form }}

        <x-filament::button type="submit">
            Submit incident
        </x-filament::button>
    </form>
</x-filament-panels::page>
