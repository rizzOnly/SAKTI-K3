<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            Filter Laporan Inventaris
        </x-slot>

        {{ $this->form }}
    </x-filament::section>

    {{ $this->table }}
</x-filament-panels::page>
