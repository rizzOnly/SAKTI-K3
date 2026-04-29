<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Gate Access – K3 PLN Sengkang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .step-badge { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; flex-shrink: 0; }
        .field-readonly { background: #f8fafc; color: #374151; border-color: #e5e7eb; cursor: not-allowed; }
        select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px; padding-right: 40px; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-slate-100">

    <div class="bg-[#003D7C] text-white py-4 px-6 flex items-center gap-4 shadow-lg sticky top-0 z-10">
        <a href="/" class="text-blue-200 hover:text-white transition text-sm">← Beranda</a>
        <div class="h-5 w-px bg-blue-500"></div>
        <div>
            <div class="font-bold text-lg">Registrasi Gate Access</div>
            <div class="text-blue-200 text-xs">PT PLN Nusantara Power – Unit Sengkang</div>
        </div>
    </div>

    {{-- Progress steps --}}
    <div class="max-w-2xl mx-auto px-4 pt-8 pb-2">
        <div class="flex items-center gap-2 mb-8">
            <div class="flex items-center gap-2">
                <div class="step-badge bg-[#003D7C] text-white">1</div>
                <span class="text-sm font-semibold text-gray-700">Isi Data Vendor</span>
            </div>
            <div class="flex-1 h-0.5 bg-gray-200 mx-2"></div>
            <div class="flex items-center gap-2">
                <div class="step-badge bg-gray-200 text-gray-500">2</div>
                <span class="text-sm text-gray-400">Survey K3</span>
            </div>
            <div class="flex-1 h-0.5 bg-gray-200 mx-2"></div>
            <div class="flex items-center gap-2">
                <div class="step-badge bg-gray-200 text-gray-500">3</div>
                <span class="text-sm text-gray-400">Terdaftar</span>
            </div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 pb-12">
        <div class="bg-white rounded-2xl p-8 shadow-[0_0_0_1px_rgba(0,0,0,.06),0_4px_24px_rgba(0,61,124,.08)]">

            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center text-2xl">🏢</div>
                <div>
                    <h1 class="font-bold text-gray-800 text-xl">Data Registrasi Vendor</h1>
                    <p class="text-gray-400 text-sm">Pilih perusahaan, data akan otomatis terisi dari sistem.</p>
                </div>
            </div>

            @if($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-600">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('vendor.registrasi.store') }}">
                @csrf

                {{-- Sembunyikan data JSON vendor untuk JS --}}
                <script id="vendor-data" type="application/json">
                    {!! json_encode($vendorsWpo->map(function($v) {
                        $pekerjas = is_string($v->pekerja_json)
                            ? json_decode($v->pekerja_json, true)
                            : ($v->pekerja_json ?? []);
                        return [
                            'id'              => $v->id,
                            'nama_vendor'     => $v->nama_vendor,
                            'nama_pekerjaan'  => $v->nama_pekerjaan ?? '-',
                            'tanggal_mulai'   => $v->tanggal_mulai?->format('d/m/Y') ?? '-',
                            'tanggal_selesai' => $v->tanggal_selesai?->format('d/m/Y') ?? '-',
                            'kontak'          => $v->kontak ?? '',
                            'pekerjas'        => array_values(array_filter(
                                is_array($pekerjas) ? array_map(fn($p) => $p['nama'] ?? null, $pekerjas) : []
                            )),
                        ];
                    })->values()) !!}
                </script>

                {{-- Pilih Perusahaan --}}
                <div class="bg-amber-50 rounded-xl p-4 mb-6 border border-amber-100">
                    <div class="font-semibold text-gray-700 mb-4 text-sm uppercase tracking-wide">
                        Pilih Perusahaan
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Nama Perusahaan <span class="text-red-500">*</span>
                        </label>
                        <select name="cms_vendor_id" id="select-vendor" onchange="onVendorChange()"
                                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 bg-white transition @error('cms_vendor_id') border-red-400 @enderror"
                                required>
                            <option value="">— Pilih perusahaan —</option>
                            @foreach($vendorsWpo as $v)
                            <option value="{{ $v->id }}" {{ old('cms_vendor_id') == $v->id ? 'selected' : '' }}>
                                {{ $v->nama_vendor }}
                            </option>
                            @endforeach
                        </select>
                        @error('cms_vendor_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Info otomatis dari vendor --}}
                    <div id="vendor-info" class="hidden space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Nama Pekerjaan</label>
                            <div id="info-pekerjaan" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm field-readonly bg-gray-50 text-gray-600 min-h-[42px]">—</div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1">Tanggal Mulai</label>
                                <div id="info-mulai" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm field-readonly bg-gray-50 text-gray-600">—</div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 mb-1">Tanggal Selesai</label>
                                <div id="info-selesai" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm field-readonly bg-gray-50 text-gray-600">—</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pilih Pekerja --}}
                <div class="bg-green-50 rounded-xl p-4 mb-6 border border-green-100">
                    <div class="font-semibold text-gray-700 mb-1 text-sm uppercase tracking-wide">
                        Pilih Pekerja yang Akan Survey
                    </div>
                    <p class="text-xs text-gray-500 mb-4">Satu sesi registrasi untuk satu pekerja.</p>

                    <div id="pekerja-wrap">
                        <div id="pekerja-placeholder" class="text-sm text-gray-400 italic py-2">
                            Pilih perusahaan terlebih dahulu.
                        </div>
                        <div id="pekerja-select-wrap" class="hidden">
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Nama Pekerja <span class="text-red-500">*</span>
                            </label>
                            <select name="pekerja_nama" id="select-pekerja"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 bg-white transition"
                                    required>
                                <option value="">— Pilih nama pekerja —</option>
                            </select>
                            @error('pekerja_nama')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Kontak PIC --}}
                <div class="bg-blue-50 rounded-xl p-4 mb-6 border border-blue-100">
                    <div class="font-semibold text-gray-700 mb-1 text-sm uppercase tracking-wide">Kontak PIC Perusahaan</div>
                    <p class="text-xs text-gray-500 mb-4">Wajib isi minimal salah satu.</p>
                    @error('kontak')
                    <div class="mb-3 bg-red-50 border border-red-200 rounded-xl px-3 py-2 text-sm text-red-600">⚠️ {{ $message }}</div>
                    @enderror
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">📱 WhatsApp PIC</label>
                            <input type="text" name="no_wa_pic" id="input-wa" value="{{ old('no_wa_pic') }}"
                                   placeholder="08xxxxxxxxxx"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">✉️ Email PIC</label>
                            <input type="email" name="email_pic" value="{{ old('email_pic') }}"
                                   placeholder="pic@perusahaan.com"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                        </div>
                    </div>
                </div>

                {{-- Info survey --}}
                <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 mb-6 flex gap-3">
                    <span class="text-2xl flex-shrink-0">📋</span>
                    <div>
                        <div class="font-semibold text-orange-800 text-sm">Langkah berikutnya: Survey K3</div>
                        <div class="text-orange-600 text-xs mt-1">
                            Setelah submit, pekerja yang dipilih akan mengikuti survey pemahaman K3.
                            Nilai harus <strong>100%</strong> untuk terdaftar di Gate Access.
                        </div>
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-[#003D7C] hover:bg-blue-900 text-white font-bold py-4 rounded-xl transition text-sm tracking-wide shadow-lg shadow-blue-900/20">
                    Lanjut ke Survey K3 →
                </button>
            </form>
        </div>
    </div>

    <script>
        const vendorData = JSON.parse(document.getElementById('vendor-data').textContent);

        function onVendorChange() {
            const id    = parseInt(document.getElementById('select-vendor').value);
            const vendor = vendorData.find(v => v.id === id);

            const infoBox     = document.getElementById('vendor-info');
            const placeholder = document.getElementById('pekerja-placeholder');
            const selectWrap  = document.getElementById('pekerja-select-wrap');
            const selectPekerja = document.getElementById('select-pekerja');

            if (!vendor) {
                infoBox.classList.add('hidden');
                placeholder.classList.remove('hidden');
                selectWrap.classList.add('hidden');
                return;
            }

            // Isi info otomatis
            document.getElementById('info-pekerjaan').textContent = vendor.nama_pekerjaan;
            document.getElementById('info-mulai').textContent     = vendor.tanggal_mulai;
            document.getElementById('info-selesai').textContent   = vendor.tanggal_selesai;

            // Isi kontak WA jika ada
            if (vendor.kontak) {
                document.getElementById('input-wa').value = vendor.kontak;
            }

            infoBox.classList.remove('hidden');

            // Isi dropdown pekerja
            selectPekerja.innerHTML = '<option value="">— Pilih nama pekerja —</option>';
            if (vendor.pekerjas && vendor.pekerjas.length > 0) {
                vendor.pekerjas.forEach(nama => {
                    const opt = document.createElement('option');
                    opt.value = nama;
                    opt.textContent = nama;
                    selectPekerja.appendChild(opt);
                });
                placeholder.classList.add('hidden');
                selectWrap.classList.remove('hidden');
            } else {
                placeholder.textContent = 'Tidak ada pekerja terdaftar untuk perusahaan ini.';
                placeholder.classList.remove('hidden');
                selectWrap.classList.add('hidden');
            }
        }

        // Jalankan saat page load jika ada old value
        window.addEventListener('DOMContentLoaded', () => {
            const sel = document.getElementById('select-vendor');
            if (sel.value) onVendorChange();
        });
    </script>
</body>
</html>
