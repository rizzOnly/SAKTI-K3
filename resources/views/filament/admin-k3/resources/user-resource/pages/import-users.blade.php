<x-filament-panels::page>

    {{-- Breadcrumb info --}}
    <div class="mb-6 flex items-center gap-3 text-sm text-gray-500">
        <a href="{{ \App\Filament\AdminK3\Resources\UserResource::getUrl('index') }}"
           class="hover:text-primary-600 transition">
            ← Kembali ke Manajemen Pegawai
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Form Upload --}}
        <div class="lg:col-span-2">
            <x-filament::section>
                <x-slot name="heading">📤 Import Data Pegawai dari Excel</x-slot>
                <x-slot name="description">
                    Upload file .xlsx berisi daftar pegawai. Data yang NIP-nya sudah ada akan diperbarui.
                </x-slot>

                <form wire:submit="import">
                    {{ $this->form }}

                    <div class="mt-6 flex gap-3">
                        <x-filament::button type="submit" icon="heroicon-m-arrow-up-tray">
                            Mulai Import
                        </x-filament::button>

                        <x-filament::button
                            wire:click="downloadTemplate"
                            color="gray"
                            icon="heroicon-m-arrow-down-tray"
                            outlined>
                            Download Template
                        </x-filament::button>
                    </div>
                </form>
            </x-filament::section>
        </div>

        {{-- Right: Panduan --}}
        <div class="space-y-4">
            <x-filament::section>
                <x-slot name="heading">📋 Format Kolom</x-slot>
                <div class="text-sm space-y-2">
                    <div class="flex items-start gap-2">
                        <span class="font-mono bg-primary-100 text-primary-700 px-2 py-0.5 rounded text-xs">nip</span>
                        <span class="text-gray-600">NIP Pegawai <span class="text-red-500">*wajib</span></span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="font-mono bg-primary-100 text-primary-700 px-2 py-0.5 rounded text-xs">nama</span>
                        <span class="text-gray-600">Nama Lengkap <span class="text-red-500">*wajib</span></span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="font-mono bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-xs">bidang</span>
                        <span class="text-gray-600">Bidang/Unit Kerja</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="font-mono bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-xs">email</span>
                        <span class="text-gray-600">Alamat email</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="font-mono bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-xs">no_wa</span>
                        <span class="text-gray-600">Nomor WhatsApp</span>
                    </div>
                </div>
            </x-filament::section>

            <x-filament::section>
                <x-slot name="heading">⚠️ Perhatian</x-slot>
                <ul class="text-sm text-gray-600 space-y-1.5 list-disc list-inside">
                    <li>NIP yang sudah ada akan <strong>diupdate</strong>, tidak diduplikasi</li>
                    <li>Password default: <code class="bg-gray-100 px-1 rounded">password123</code></li>
                    <li>Semua pegawai baru otomatis dapat role <strong>pegawai</strong></li>
                    <li>Nomor WA bisa format <code class="bg-gray-100 px-1 rounded">08xx</code> atau <code class="bg-gray-100 px-1 rounded">628xx</code></li>
                    <li>Baris yang error akan di-skip, import tetap lanjut</li>
                </ul>
            </x-filament::section>
        </div>

    </div>

</x-filament-panels::page>
