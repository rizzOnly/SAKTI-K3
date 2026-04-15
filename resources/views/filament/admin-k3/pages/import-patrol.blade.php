<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <x-filament::section>
                <x-slot name="heading">📥 Import Jadwal Patrol dari Excel</x-slot>
                <x-slot name="description">
                    Upload file jadwal P2K3. Kolom A = Nama Pegawai, kolom berikutnya = tanggal, isi = lokasi unit.
                </x-slot>
                <form wire:submit="import">
                    {{ $this->form }}
                    <div class="mt-6">
                        <x-filament::button type="submit" icon="heroicon-m-arrow-up-tray">
                            Mulai Import
                        </x-filament::button>
                    </div>
                </form>
            </x-filament::section>
        </div>
        <div>
            <x-filament::section>
                <x-slot name="heading">📋 Format yang Didukung</x-slot>
                <div class="text-sm space-y-2 text-gray-600">
                    <p>File Excel dengan format:</p>
                    <ul class="list-disc list-inside space-y-1 text-xs mt-2">
                        <li>Baris 1 = Header: "Nama Pegawai" + tanggal-tanggal</li>
                        <li>Kolom A = Nama Pegawai</li>
                        <li>Sel berisi lokasi = jadwal patrol hari itu</li>
                        <li>Sel kosong = tidak bertugas hari itu</li>
                        <li>Hanya tanggal sesuai bulan yang dipilih yang diimpor</li>
                    </ul>
                    <p class="mt-3 text-xs text-amber-600 font-medium">
                        ⚠️ Import akan menghapus jadwal lama untuk bulan/tahun yang sama.
                    </p>
                </div>
            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>
