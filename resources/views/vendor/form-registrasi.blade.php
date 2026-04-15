{{-- ============================================================ --}}
{{-- FILE: resources/views/vendor/form-registrasi.blade.php     --}}
{{-- Form Registrasi Gate Access Vendor                         --}}
{{-- ============================================================ --}}

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
                    <p class="text-gray-400 text-sm">Setelah mengisi form ini, setiap pekerja wajib mengikuti survey K3.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('vendor.registrasi.store') }}">
                @csrf

                {{-- Info perusahaan --}}
                <div class="bg-amber-50 rounded-xl p-4 mb-6 border border-amber-100">
                    <div class="font-semibold text-gray-700 mb-4 text-sm uppercase tracking-wide">Informasi Perusahaan & Pekerjaan</div>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Perusahaan <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan') }}"
                                   placeholder="Contoh: CV Marezho"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 transition @error('nama_perusahaan') border-red-400 @enderror" required>
                            @error('nama_perusahaan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Pekerjaan <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_pekerjaan" value="{{ old('nama_pekerjaan') }}"
                                   placeholder="Contoh: Jasa Pemasangan Kanopi Gudang Utama"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 transition @error('nama_pekerjaan') border-red-400 @enderror" required>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Mulai <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 transition" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Selesai <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 transition" required>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kontak PIC --}}
                <div class="bg-blue-50 rounded-xl p-4 mb-6 border border-blue-100">
                    <div class="font-semibold text-gray-700 mb-1 text-sm uppercase tracking-wide">Kontak PIC Perusahaan</div>
                    <p class="text-xs text-gray-500 mb-4">Wajib isi minimal salah satu.</p>
                    @error('kontak')<div class="mb-3 bg-red-50 border border-red-200 rounded-xl px-3 py-2 text-sm text-red-600">⚠️ {{ $message }}</div>@enderror
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">📱 WhatsApp PIC</label>
                            <input type="text" name="no_wa_pic" value="{{ old('no_wa_pic') }}"
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

                {{-- Daftar Pekerja --}}
                <div class="mb-8">
                    <div class="font-semibold text-gray-700 mb-1">Daftar Pekerja <span class="text-red-500">*</span></div>
                    <p class="text-xs text-gray-500 mb-4">Setiap pekerja akan mengikuti survey K3 secara individual setelah registrasi ini.</p>
                    @error('pekerjas')<p class="text-red-500 text-xs mb-2">{{ $message }}</p>@enderror

                    <div id="pekerja-list" class="space-y-3">
                        @php $oldPekerjas = old('pekerjas', [[]]); @endphp
                        @foreach($oldPekerjas as $i => $p)
                        <div class="pekerja-row bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pekerja #<span class="row-num">{{ $i + 1 }}</span></span>
                                @if($i > 0)
                                <button type="button" onclick="removeRow(this)" class="text-gray-300 hover:text-red-400 transition text-xl leading-none">×</button>
                                @endif
                            </div>
                            <div class="grid grid-cols-1 gap-3">
                                <input type="text" name="pekerjas[{{ $i }}][nama]"
                                       value="{{ $p['nama'] ?? '' }}"
                                       placeholder="Nama lengkap pekerja"
                                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 bg-white" required>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <button type="button" onclick="addPekerja()"
                            class="mt-3 flex items-center gap-2 text-sm text-amber-600 hover:text-amber-800 font-semibold transition">
                        <span class="text-lg">+</span> Tambah Pekerja
                    </button>
                </div>

                {{-- Info survey --}}
                <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 mb-6 flex gap-3">
                    <span class="text-2xl flex-shrink-0">📋</span>
                    <div>
                        <div class="font-semibold text-orange-800 text-sm">Langkah berikutnya: Survey K3</div>
                        <div class="text-orange-600 text-xs mt-1">Setelah submit, setiap pekerja akan mengikuti survey pemahaman K3 (Safety Golden Rules). Nilai harus <strong>100%</strong> untuk terdaftar di Gate Access.</div>
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
        let rowCount = {{ count($oldPekerjas ?? [1]) }};

        function addPekerja() {
            const idx = rowCount++;
            const div = document.createElement('div');
            div.className = 'pekerja-row bg-gray-50 rounded-xl p-4 border border-gray-100';
            div.innerHTML = `
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pekerja #${idx + 1}</span>
                    <button type="button" onclick="removeRow(this)" class="text-gray-300 hover:text-red-400 transition text-xl leading-none">×</button>
                </div>
                <div class="grid grid-cols-1 gap-3">
                    <input type="text" name="pekerjas[${idx}][nama]" placeholder="Nama lengkap pekerja"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 bg-white" required>
                </div>`;
            document.getElementById('pekerja-list').appendChild(div);
        }

        function removeRow(btn) {
            btn.closest('.pekerja-row').remove();
        }
    </script>
</body>
</html>
