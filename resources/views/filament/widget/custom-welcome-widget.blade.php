<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between gap-x-3">

            {{-- Bagian Kiri: Teks Sambutan --}}
            <div class="flex-1">
                <h2 class="text-xl font-bold text-gray-950 dark:text-white">
                    Welcome, {{ auth()->user()->name }} 👋
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Selamat bekerja dan jangan lupa utamakan keselamatan!
                </p>
            </div>

            {{-- Bagian Kanan: Tombol Sign Out --}}
            <div class="flex-shrink-0">
                <x-filament::button
                    href="/logout"
                    tag="a"
                    color="gray"
                    icon="heroicon-m-arrow-right-on-rectangle">
                    Sign out
                </x-filament::button>
            </div>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>
