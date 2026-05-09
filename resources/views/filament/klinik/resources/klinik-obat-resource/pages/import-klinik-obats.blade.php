<x-filament-panels::page>

    {{-- Breadcrumb info --}}
    <div class="mb-6 flex items-center gap-3 text-sm text-gray-500">
        <a href="{{ \App\Filament\Klinik\Resources\KlinikObatResource::getUrl('index') }}"
           class="hover:text-primary-600 transition">
            ← Kembali ke Data Obat
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Form Upload --}}
        <div class="lg:col-span-2">
            <x-filament::section>
                <x-slot name="heading">📤 Import Data Obat dari Excel</x-slot>
                <x-slot name="description">
                    Upload file .xlsx berisi daftar obat. Data dengan Kode Obat yang sama akan diperbarui.
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
                        <span class="font-mono bg-primary-100 text-primary-700 px-2 py-0.5 rounded text-xs">kode_obat</span>
                        <span class="text-gray-600">Kode Obat <span class="text-red-500">*wajib</span></span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="font-mono bg-primary-100 text-primary-700 px-2 py-0.5 rounded text-xs">nama_barang</span>
                        <span class="text-gray-600">Nama Obat <span class="text-red-500">*wajib</span></span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="font-mono bg-primary-100 text-primary-700 px-2 py-0.5 rounded text-xs">satuan</span>
                        <span class="text-gray-600">Satuan <span class="text-red-500">*wajib</span></span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="font-mono bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-xs">stok</span>
                        <span class="text-gray-600">Stok <span class="text-red-500">*wajib</span></span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="font-mono bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-xs">min_stok</span>
                        <span class="text-gray-600">Minimum Stok <span class="text-red-500">*wajib</span></span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="font-mono bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-xs">tanggal_masuk</span>
                        <span class="text-gray-600">Tanggal Masuk (YYYY-MM-DD atau kosong)</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="font-mono bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-xs">tanggal_exp</span>
                        <span class="text-gray-600">Tanggal Expired (YYYY-MM-DD atau kosong)</span>
                    </div>
                </div>
            </x-filament::section>

            <x-filament::section>
                <x-slot name="heading">⚠️ Perhatian</x-slot>
                <ul class="text-sm text-gray-600 space-y-1.5 list-disc list-inside">
                    <li>Kode Obat yang sudah ada akan <strong>diupdate</strong>, tidak diduplikat</li>
                    <li>Kolom wajib: kode_obat, nama_barang, satuan, stok, min_stok</li>
                    <li>Format tanggal: <code class="bg-gray-100 px-1 rounded">YYYY-MM-DD</code> (contoh: 2026-12-31)</li>
                    <li>EWS: Notifikasi warning muncul jika tanggal_exp < 30 hari dari sekarang</li>
                    <li>Baris yang error akan di-skip, import tetap lanjut</li>
                </ul>
            </x-filament::section>
        </div>

    </div>

</x-filament-panels::page>
