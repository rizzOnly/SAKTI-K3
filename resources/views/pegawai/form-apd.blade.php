<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan APD – K3 PLN Sengkang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .tab-content { display: none; }
        .tab-content.active { display: block; animation: fadeIn .25s ease; }
        @keyframes fadeIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:none; } }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100">

    <div class="bg-[#003D7C] text-white py-4 px-6 flex items-center gap-4 shadow-lg">
        <a href="/" class="text-blue-200 hover:text-white transition text-sm">← Beranda</a>
        <div class="h-5 w-px bg-blue-500"></div>
        <div>
            <div class="font-bold text-lg">Pengajuan APD</div>
            <div class="text-blue-200 text-xs">PT PLN Nusantara Power – Unit Sengkang</div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-10">

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-2xl p-5 mb-6 flex items-start gap-3">
            <span class="text-green-500 text-xl flex-shrink-0">✓</span>
            <div>
                <div class="font-bold text-green-800">Pengajuan Berhasil!</div>
                <div class="text-green-700 text-sm mt-0.5">{{ session('success') }}</div>
                <a href="{{ route('pegawai.apd') }}" class="text-sm text-green-700 underline mt-2 inline-block">Ajukan lagi</a>
            </div>
        </div>
        @endif

        {{-- TAB SELECTOR --}}
        <div class="flex gap-3 mb-6">
            <button id="tab-ambil-btn" onclick="switchTab('ambil')" class="tab-btn flex-1 flex items-center justify-center gap-3 py-4 px-5 rounded-2xl border-2 transition-all">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl flex-shrink-0 bg-blue-100 text-blue-700">📦</div>
                <div class="text-left">
                    <div class="font-bold text-sm">Ambil APD</div>
                    <div class="text-xs text-gray-400">Consumable / habis pakai</div>
                </div>
            </button>
            <button id="tab-pinjam-btn" onclick="switchTab('pinjam')" class="tab-btn flex-1 flex items-center justify-center gap-3 py-4 px-5 rounded-2xl border-2 transition-all">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl flex-shrink-0 bg-teal-100 text-teal-700">🔄</div>
                <div class="text-left">
                    <div class="font-bold text-sm">Pinjam APD</div>
                    <div class="text-xs text-gray-400">Returnable / dikembalikan</div>
                </div>
            </button>
        </div>

        <div id="tab-placeholder" class="bg-white rounded-2xl p-10 text-center shadow-sm">
            <div class="text-4xl mb-3">🦺</div>
            <div class="font-semibold text-gray-700">Pilih jenis pengajuan</div>
            <div class="text-gray-400 text-sm mt-1">Klik salah satu tombol di atas untuk melanjutkan.</div>
        </div>

        {{-- FORM AMBIL APD --}}
        <div id="tab-ambil" class="tab-content">
            <div class="bg-white rounded-2xl p-8 shadow-[0_0_0_1px_rgba(0,0,0,.06),0_4px_24px_rgba(0,61,124,.08)]">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-xl">📦</div>
                    <div>
                        <h2 class="font-bold text-gray-800 text-lg">Form Pengambilan APD</h2>
                        <p class="text-gray-400 text-xs">Untuk APD consumable / habis pakai</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('pegawai.apd.ambil.store') }}">
                    @csrf
                    <input type="hidden" name="_form_type" value="ambil">
                    @include('pegawai._form-nip-field', ['formId' => 'ambil'])
                    @include('pegawai._form-tanggal-field')

                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Item APD <span class="text-red-500">*</span></label>
                        <div id="ambil-items" class="space-y-3">
                            <div class="item-row flex gap-3">
                                <select name="items[0][apd_item_id]" class="flex-1 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white" required>
                                    <option value="">-- Pilih APD --</option>
                                    @foreach($apdConsumable as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_barang }} (Stok: {{ $item->stok }})</option>
                                    @endforeach
                                </select>
                                <input type="number" name="items[0][jumlah]" value="1" min="1" class="w-24 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-center" required>
                            </div>
                        </div>
                        <button type="button" onclick="addItem('ambil-items', ambilOpts)" class="mt-3 text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">+ Tambah Item</button>
                    </div>

                    @include('pegawai._form-catatan-field')
                    @include('pegawai._form-kontak-field', ['color' => 'blue'])
                    <button type="submit" class="w-full bg-[#003D7C] hover:bg-blue-900 text-white font-bold py-4 rounded-xl transition text-sm tracking-wide shadow-lg shadow-blue-900/20">Kirim Pengajuan Ambil →</button>
                </form>
            </div>
        </div>

        {{-- FORM PINJAM APD --}}
        <div id="tab-pinjam" class="tab-content">
            <div class="bg-white rounded-2xl p-8 shadow-[0_0_0_1px_rgba(0,0,0,.06),0_4px_24px_rgba(13,148,136,.1)]">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-teal-100 rounded-xl flex items-center justify-center text-xl">🔄</div>
                    <div>
                        <h2 class="font-bold text-gray-800 text-lg">Form Peminjaman APD</h2>
                        <p class="text-gray-400 text-xs">Untuk APD returnable / dikembalikan</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('pegawai.apd.pinjam.store') }}">
                    @csrf
                    <input type="hidden" name="_form_type" value="pinjam">
                    @include('pegawai._form-nip-field', ['formId' => 'pinjam'])
                    @include('pegawai._form-tanggal-field')

                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Rencana Tanggal Kembali <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_kembali_rencana" value="{{ old('tanggal_kembali_rencana') }}" min="{{ today()->addDay()->format('Y-m-d') }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 transition" required>
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Item APD <span class="text-red-500">*</span></label>
                        <div id="pinjam-items" class="space-y-3">
                            <div class="item-row flex gap-3">
                                <select name="items[0][apd_item_id]" class="flex-1 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 bg-white" required>
                                    <option value="">-- Pilih APD --</option>
                                    @foreach($apdReturnable as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_barang }} (Stok: {{ $item->stok }})</option>
                                    @endforeach
                                </select>
                                <input type="number" name="items[0][jumlah]" value="1" min="1" class="w-24 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 text-center" required>
                            </div>
                        </div>
                        <button type="button" onclick="addItem('pinjam-items', pinjamOpts)" class="mt-3 text-sm text-teal-600 hover:text-teal-800 font-medium flex items-center gap-1">+ Tambah Item</button>
                    </div>

                    @include('pegawai._form-catatan-field')
                    @include('pegawai._form-kontak-field', ['color' => 'teal'])
                    <button type="submit" class="w-full bg-teal-700 hover:bg-teal-800 text-white font-bold py-4 rounded-xl transition text-sm tracking-wide shadow-lg shadow-teal-900/20">Kirim Pengajuan Pinjam →</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const ambilOpts = `@foreach($apdConsumable as $i)<option value="{{ $i->id }}">{{ $i->nama_barang }} (Stok: {{ $i->stok }})</option>@endforeach`;
        const pinjamOpts = `@foreach($apdReturnable as $i)<option value="{{ $i->id }}">{{ $i->nama_barang }} (Stok: {{ $i->stok }})</option>@endforeach`;
        let itemIdx = { 'ambil-items': 1, 'pinjam-items': 1 };

        function addItem(containerId, opts) {
            const container = document.getElementById(containerId);
            const color = containerId === 'ambil-items' ? 'blue' : 'teal';
            const idx = itemIdx[containerId]++;
            const div = document.createElement('div');
            div.className = 'item-row flex gap-3';
            div.innerHTML = `
                <select name="items[${idx}][apd_item_id]" class="flex-1 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-${color}-500 bg-white" required>
                    <option value="">-- Pilih APD --</option>${opts}
                </select>
                <input type="number" name="items[${idx}][jumlah]" value="1" min="1" class="w-24 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-${color}-500 text-center" required>
                <button type="button" onclick="this.closest('.item-row').remove()" class="text-gray-300 hover:text-red-400 transition text-2xl leading-none">×</button>`;
            container.appendChild(div);
        }

        function switchTab(tab) {
            document.getElementById('tab-placeholder').classList.add('hidden');
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            document.getElementById('tab-' + tab).classList.add('active');

            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('border-blue-500', 'bg-blue-50', 'border-teal-500', 'bg-teal-50');
                btn.classList.add('border-gray-200', 'bg-white');
            });

            const activeBtn = document.getElementById('tab-' + tab + '-btn');
            activeBtn.classList.remove('border-gray-200', 'bg-white');
            activeBtn.classList.add(tab === 'ambil' ? 'border-blue-500' : 'border-teal-500', tab === 'ambil' ? 'bg-blue-50' : 'bg-teal-50');
        }

        // Live NIP Check (Disesuaikan untuk Multi-Tab)
        ['ambil', 'pinjam'].forEach(formId => {
            let nipTimer;
            const input = document.getElementById(`nip-input-${formId}`);
            if(!input) return;

            input.addEventListener('input', function() {
                clearTimeout(nipTimer);
                const nip = this.value.trim();
                document.getElementById(`nip-found-${formId}`).classList.add('hidden');
                document.getElementById(`nip-not-found-${formId}`).classList.add('hidden');
                document.getElementById(`nip-ok-${formId}`).classList.add('hidden');

                if (nip.length < 5) return;

                const spinner = document.getElementById(`nip-spinner-${formId}`);
                spinner.classList.remove('hidden');

                nipTimer = setTimeout(async () => {
                    try {
                        const res = await fetch(`/pegawai/api/cek-nip?nip=${encodeURIComponent(nip)}`);
                        const data = await res.json();
                        spinner.classList.add('hidden');
                        if (data.found) {
                            document.getElementById(`nip-nama-${formId}`).textContent = data.nama;
                            document.getElementById(`nip-bidang-${formId}`).textContent = data.bidang ? `– ${data.bidang}` : '';
                            document.getElementById(`nip-found-${formId}`).classList.remove('hidden');
                            document.getElementById(`nip-ok-${formId}`).classList.remove('hidden');
                        } else {
                            document.getElementById(`nip-not-found-${formId}`).classList.remove('hidden');
                        }
                    } catch (e) {
                        spinner.classList.add('hidden');
                    }
                }, 600);
            });
        });

        // Tab otomatis terbuka jika ada validasi error
        @if($errors->any() && old('_form_type'))
            switchTab("{{ old('_form_type') }}");
        @endif
    </script>
</body>
</html>
