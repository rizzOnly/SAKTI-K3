<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengambilan APD – K3 PLN Sengkang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100">

    <div class="bg-[#003D7C] text-white py-4 px-6 flex items-center gap-4 shadow-lg">
        <a href="/" class="text-blue-200 hover:text-white transition text-sm">← Beranda</a>
        <div class="h-5 w-px bg-blue-500"></div>
        <div>
            <div class="font-bold text-lg">Form Pengambilan APD</div>
            <div class="text-blue-200 text-xs">PT PLN Nusantara Power – Unit Sengkang</div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-10">

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-2xl p-6 mb-6 flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 text-green-600 text-xl">✓</div>
            <div>
                <div class="font-bold text-green-800">Pengajuan Berhasil!</div>
                <div class="text-green-700 text-sm mt-1">{{ session('success') }}</div>
                <a href="{{ route('pegawai.pengambilan') }}" class="inline-block mt-3 text-sm text-green-700 underline">Ajukan lagi</a>
            </div>
        </div>
        @endif

        <div class="bg-white rounded-2xl p-8 shadow-[0_0_0_1px_rgba(0,0,0,.06),0_4px_24px_rgba(0,61,124,.08)]">
            <h1 class="text-2xl font-bold text-gray-800 mb-1">Ajukan Pengambilan APD</h1>
            <p class="text-gray-400 text-sm mb-8">Isi form di bawah. Pengajuan akan diproses oleh Admin K3.</p>

            <form method="POST" action="{{ route('pegawai.pengambilan.store') }}">
                @csrf

                {{-- NIP dengan live check --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">NIP Pegawai <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" id="nip-input" name="nip" value="{{ old('nip') }}"
                               placeholder="Masukkan NIP Anda"
                               class="w-full border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition pr-10 @error('nip') border-red-400 bg-red-50 @else border-gray-200 @enderror"
                               required autocomplete="off">
                        <div id="nip-spinner" class="hidden absolute right-3 top-3.5 w-4 h-4 border-2 border-blue-300 border-t-blue-600 rounded-full animate-spin"></div>
                        <div id="nip-check-ok" class="hidden absolute right-3 top-3 text-green-500 text-lg">✓</div>
                    </div>
                    {{-- NIP tidak ditemukan --}}
                    @error('nip')
                    <div class="mt-2 bg-red-50 border border-red-200 rounded-xl p-3 flex items-start gap-2">
                        <span class="text-red-500 text-lg flex-shrink-0">⚠️</span>
                        <div>
                            <p class="text-red-700 text-sm font-medium">{{ $message }}</p>
                            <p class="text-red-500 text-xs mt-1">Belum terdaftar? Hubungi Admin K3 di <strong>pos K3</strong> atau <strong>ext. 101</strong>.</p>
                        </div>
                    </div>
                    @enderror
                    <div id="nip-not-found" class="hidden mt-2 bg-amber-50 border border-amber-200 rounded-xl p-3 flex items-start gap-2">
                        <span class="text-amber-500 text-lg flex-shrink-0">⚠️</span>
                        <div>
                            <p class="text-amber-700 text-sm font-medium">NIP tidak ditemukan dalam sistem.</p>
                            <p class="text-amber-600 text-xs mt-1">Hubungi Admin K3 di pos K3 atau ext. 101 untuk mendaftar.</p>
                        </div>
                    </div>
                    <div id="nip-found" class="hidden mt-2 bg-green-50 border border-green-200 rounded-xl px-3 py-2 text-sm text-green-700">
                        <span class="font-semibold">👤 <span id="nip-nama"></span></span>
                        <span id="nip-bidang" class="text-green-500 ml-2 text-xs"></span>
                    </div>
                </div>

                {{-- Tanggal --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Pengajuan <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_pengajuan"
                           value="{{ old('tanggal_pengajuan', today()->format('Y-m-d')) }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                           required>
                </div>

                {{-- Item APD --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Item APD <span class="text-red-500">*</span></label>
                    <div id="items-container" class="space-y-3">
                        <div class="item-row flex gap-3">
                            <select name="items[0][apd_item_id]"
                                    class="flex-1 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white" required>
                                <option value="">-- Pilih APD --</option>
                                @foreach($apdItems as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_barang }} (Stok: {{ $item->stok }} {{ $item->satuan }})</option>
                                @endforeach
                            </select>
                            <input type="number" name="items[0][jumlah]" value="1" min="1"
                                   class="w-24 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-center" required>
                        </div>
                    </div>
                    <button type="button" onclick="addItem()"
                            class="mt-3 text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1 transition">
                        <span class="text-lg leading-none">+</span> Tambah Item
                    </button>
                    @error('items') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Catatan --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan</label>
                    <textarea name="catatan" rows="2" placeholder="Keperluan / keterangan..."
                              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('catatan') }}</textarea>
                </div>

                {{-- ═══ KONTAK NOTIFIKASI ════════════════════════ --}}
                <div class="mb-8 bg-blue-50 rounded-2xl p-5 border border-blue-100">
                    <div class="font-semibold text-gray-700 mb-1 flex items-center gap-2">
                        🔔 Konfirmasi dikirim ke:
                    </div>
                    <p class="text-xs text-gray-500 mb-4">Isi minimal salah satu agar kami bisa mengirim konfirmasi pengajuan Anda.</p>

                    @error('kontak')
                    <div class="mb-3 bg-red-50 border border-red-200 rounded-xl px-3 py-2 text-sm text-red-600">
                        ⚠️ {{ $message }}
                    </div>
                    @enderror

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                                📱 Nomor WhatsApp
                            </label>
                            <input type="text" name="no_wa_pengirim"
                                   value="{{ old('no_wa_pengirim') }}"
                                   placeholder="08xxxxxxxxxx"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition bg-white @error('no_wa_pengirim') border-red-400 @enderror">
                            @error('no_wa_pengirim') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                                ✉️ Email
                            </label>
                            <input type="email" name="email_pengirim"
                                   value="{{ old('email_pengirim') }}"
                                   placeholder="nama@email.com"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition bg-white @error('email_pengirim') border-red-400 @enderror">
                            @error('email_pengirim') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-[#003D7C] hover:bg-blue-900 text-white font-bold py-4 rounded-xl transition text-sm tracking-wide shadow-lg shadow-blue-900/20">
                    Kirim Pengajuan →
                </button>
            </form>
        </div>
    </div>

    <script>
        let idx = 1;
        const opts = `@foreach($apdItems as $item)<option value="{{ $item->id }}">{{ $item->nama_barang }} (Stok: {{ $item->stok }} {{ $item->satuan }})</option>@endforeach`;

        function addItem() {
            const d = document.createElement('div');
            d.className = 'item-row flex gap-3';
            d.innerHTML = `
                <select name="items[${idx}][apd_item_id]" class="flex-1 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white" required>
                    <option value="">-- Pilih APD --</option>${opts}
                </select>
                <input type="number" name="items[${idx}][jumlah]" value="1" min="1"
                       class="w-24 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-center" required>
                <button type="button" onclick="this.closest('.item-row').remove()" class="text-gray-300 hover:text-red-400 transition text-xl leading-none">×</button>`;
            document.getElementById('items-container').appendChild(d);
            idx++;
        }

        // Live NIP check
        let nipTimer;
        document.getElementById('nip-input').addEventListener('input', function() {
            clearTimeout(nipTimer);
            const nip = this.value.trim();
            const found = document.getElementById('nip-found');
            const notFound = document.getElementById('nip-not-found');
            const spinner = document.getElementById('nip-spinner');
            const ok = document.getElementById('nip-check-ok');

            found.classList.add('hidden');
            notFound.classList.add('hidden');
            ok.classList.add('hidden');

            if (nip.length < 5) return;

            spinner.classList.remove('hidden');
            nipTimer = setTimeout(async () => {
                try {
                    const res = await fetch(`/pegawai/api/cek-nip?nip=${encodeURIComponent(nip)}`);
                    const data = await res.json();
                    spinner.classList.add('hidden');
                    if (data.found) {
                        document.getElementById('nip-nama').textContent = data.nama;
                        document.getElementById('nip-bidang').textContent = data.bidang ? `– ${data.bidang}` : '';
                        found.classList.remove('hidden');
                        ok.classList.remove('hidden');
                    } else {
                        notFound.classList.remove('hidden');
                    }
                } catch (e) {
                    spinner.classList.add('hidden');
                }
            }, 600);
        });
    </script>
</body>
</html>
