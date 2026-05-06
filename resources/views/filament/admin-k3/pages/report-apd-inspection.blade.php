<x-filament-panels::page>
    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
        <!-- Header -->
        <div class="mb-6 border-b pb-4">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                        Laporan Inspeksi Bulanan APD
                    </h1>
                    <p class="text-gray-600 dark:text-gray-300 mt-1">
                        Bulan: <span class="font-semibold">{{ $inspection->bulan }}</span>
                        <span class="mx-2">|</span>
                        Tanggal Inspeksi: {{ $inspection->tanggal_inspeksi->format('d/m/Y') }}
                    </p>
                    <p class="text-gray-600 dark:text-gray-300">
                        Inspektur: {{ $inspection->user->name ?? 'Unknown' }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Status:
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            {{ $inspection->status === 'final' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ strtoupper($inspection->status) }}
                        </span>
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Dibuat: {{ $inspection->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            @if($inspection->catatan)
            <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-400">
                <p class="text-sm text-blue-700 dark:text-blue-200">{{ $inspection->catatan }}</p>
            </div>
            @endif
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                <p class="text-sm text-blue-600 dark:text-blue-300">Total Item Diinspeksi</p>
                <p class="text-2xl font-bold text-blue-800 dark:text-blue-100">{{ $totalItems }}</p>
            </div>
            <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg">
                <p class="text-sm text-green-600 dark:text-green-300">Layak (Baik)</p>
                <p class="text-2xl font-bold text-green-800 dark:text-green-100">{{ $fitItems }}</p>
            </div>
            <div class="bg-red-50 dark:bg-red-900 p-4 rounded-lg">
                <p class="text-sm text-red-600 dark:text-red-300">Tidak Layak</p>
                <p class="text-2xl font-bold text-red-800 dark:text-red-100">{{ $unfitItems }}</p>
            </div>
            <div class="bg-orange-50 dark:bg-orange-900 p-4 rounded-lg">
                <p class="text-sm text-orange-600 dark:text-orange-300">Total Kecacatan Ditemukan</p>
                <p class="text-2xl font-bold text-orange-800 dark:text-orange-100">{{ $totalUnfitCriteria }}</p>
            </div>
        </div>

        <!-- Check Details by Type -->
        <div class="space-y-6">
            @foreach($groupedDetails as $type => $details)
            <div class="border rounded-lg overflow-hidden">
                <div class="bg-gray-100 dark:bg-gray-700 px-4 py-2 font-semibold text-gray-800 dark:text-gray-100">
                    {{ ucfirst($type) }} ({{ $details->count() }} item)
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-2 text-left">Nama Barang</th>
                            <th class="px-4 py-2 text-center">Kondisi Sebelum</th>
                            <th class="px-4 py-2 text-center">Kondisi Sesudah</th>
                            <th class="px-4 py-2 text-center">Layak</th>
                            <th class="px-4 py-2 text-center">Kriteria Cacat</th>
                            <th class="px-4 py-2">Alasan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-gray-700">
                        @foreach($details as $detail)
                        <tr class="{{ $detail->is_tidak_layak ? 'bg-red-50 dark:bg-red-900' : '' }}">
                            <td class="px-4 py-3">
                                {{ $detail->apdItem->nama_barang }}
                                <span class="text-xs text-gray-500">({{ $detail->apdItem->kode_barang }})</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($detail->kondisi_sebelum === 'baik')
                                    <span class="text-green-600 font-semibold">BAIK</span>
                                @elseif($detail->kondisi_sebelum === 'rusak')
                                    <span class="text-red-600 font-semibold">RUSAK</span>
                                @else
                                    <span class="text-yellow-600 font-semibold">EXPIRED</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($detail->kondisi_sesudah === 'baik')
                                    <span class="text-green-600 font-semibold">BAIK</span>
                                @elseif($detail->kondisi_sesudah === 'rusak')
                                    <span class="text-red-600 font-semibold">RUSAK</span>
                                @else
                                    <span class="text-yellow-600 font-semibold">EXPIRED</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($detail->is_tidak_layak)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                        TIDAK
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                        LAYAK
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($detail->kriteria_ceklist)
                                    @php
                                        $unfitCtr = $detail->unfit_criteria_count;
                                    @endphp
                                    <span class="px-2 py-1 rounded text-xs {{ $unfitCtr > 0 ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $unfitCtr }} cacat
                                    </span>
                                    @if($unfitCtr > 0)
                                        <ul class="mt-1 text-xs text-red-600">
                                            @foreach($detail->kriteria_ceklist as $kriteria)
                                                @if(isset($kriteria['is_unfit']) && $kriteria['is_unfit'])
                                                    <li>• {{ $kriteria['nama'] }}@if(!empty($kriteria['keterangan'])) ({{ $kriteria['keterangan'] }})@endif</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-600">
                                {{ $detail->alasan_tidak_layak ?: '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endforeach
        </div>

        <!-- Summary -->
        <div class="mt-8 grid grid-cols-2 gap-8">
            <div>
                <h3 class="font-bold mb-2">Catatan Tambahan:</h3>
                <p class="text-sm text-gray-600">{{ $inspection->catatan ?: 'Tidak ada catatan tambahan.' }}</p>
            </div>
            <div>
                <h3 class="font-bold mb-2">Tindakan:</h3>
                @if($inspection->status === 'final')
                    <p class="text-sm text-green-600">Stok APD telah diperbarui sesuai hasil inspeksi.</p>
                @else
                    <p class="text-sm text-yellow-600">Inspeksi belum difinalkan.</p>
                @endif
            </div>
        </div>

        <div class="mt-8 pt-6 border-t flex justify-end">
            <p class="text-xs text-gray-400">Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</x-filament-panels::page>
