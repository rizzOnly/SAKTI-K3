<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Klinik – K3 PLN Sengkang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .slot-btn { transition: all .15s ease; }
        .slot-btn:disabled { opacity: .35; cursor: not-allowed; }
        .slot-btn.selected { @apply bg-teal-600 text-white border-teal-600 shadow-md; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-teal-50 via-slate-50 to-white">

    <div class="bg-teal-700 text-white py-4 px-6 flex items-center gap-4 shadow-lg">
        <a href="/" class="text-teal-200 hover:text-white transition text-sm">← Beranda</a>
        <div class="h-5 w-px bg-teal-500"></div>
        <div>
            <div class="font-bold text-lg">Booking Klinik</div>
            <div class="text-teal-200 text-xs">PT PLN Nusantara Power – Unit Sengkang</div>
        </div>
    </div>

    <div class="max-w-xl mx-auto px-4 py-10">

        @if(session('success'))
        <div class="bg-teal-50 border border-teal-200 rounded-2xl p-6 mb-6 flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <div class="font-bold text-teal-800">Booking Berhasil!</div>
                <div class="text-teal-700 text-sm mt-1">{{ session('success') }}</div>
                <a href="{{ route('pegawai.booking') }}" class="inline-block mt-3 text-sm text-teal-700 underline">Booking lagi</a>
            </div>
        </div>
        @endif

        <div class="bg-white rounded-2xl p-8 shadow-[0_0_0_1px_rgba(0,0,0,.06),0_4px_24px_rgba(13,148,136,.1)]">
            <h1 class="text-2xl font-bold text-gray-800 mb-1">Booking Appointment Klinik</h1>
            <p class="text-gray-400 text-sm mb-8">Pilih dokter, tanggal, dan jam yang tersedia.</p>

            <form method="POST" action="{{ route('pegawai.booking.store') }}" id="booking-form">
                @csrf

                {{-- NIP dengan live check (Diadaptasi dari form APD) --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">NIP Pegawai <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" id="nip-input" name="nip" value="{{ old('nip') }}"
                               placeholder="Masukkan NIP Anda"
                               class="w-full border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 transition pr-10 @error('nip') border-red-400 bg-red-50 @else border-gray-200 @enderror"
                               required autocomplete="off">
                        <div id="nip-spinner" class="hidden absolute right-3 top-3.5 w-4 h-4 border-2 border-teal-300 border-t-teal-600 rounded-full animate-spin"></div>
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

                {{-- Bidang --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Bidang / Unit Kerja</label>
                    <input type="text" name="bidang" id="bidang-input" value="{{ old('bidang') }}"
                           readonly
                           class="w-full border border-gray-200 bg-gray-50 text-gray-500 cursor-not-allowed rounded-xl px-4 py-3 text-sm focus:outline-none transition">
                </div>

                {{-- Jenis Kelamin --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <div class="flex gap-3">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="jenis_kelamin" value="L" class="sr-only" required
                                   {{ old('jenis_kelamin') === 'L' ? 'checked' : '' }}>
                            <div class="jk-option border-2 border-gray-200 rounded-xl p-3 text-center text-sm font-semibold text-gray-600 hover:border-teal-400 transition">
                                👨 Laki-laki
                            </div>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="jenis_kelamin" value="P" class="sr-only"
                                   {{ old('jenis_kelamin') === 'P' ? 'checked' : '' }}>
                            <div class="jk-option border-2 border-gray-200 rounded-xl p-3 text-center text-sm font-semibold text-gray-600 hover:border-teal-400 transition">
                                👩 Perempuan
                            </div>
                        </label>
                    </div>
                    @error('jenis_kelamin')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Dokter --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Dokter <span class="text-red-500">*</span></label>
                    <select name="dokter_id" id="dokter-select"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 transition bg-white"
                            required onchange="loadSlots()">
                        <option value="">-- Pilih Dokter --</option>
                        @foreach($dokters as $dokter)
                        <option value="{{ $dokter->id }}" {{ old('dokter_id') == $dokter->id ? 'selected' : '' }}>
                            {{ $dokter->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tanggal --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal" id="tanggal-input"
                           value="{{ old('tanggal', today()->addDay()->format('Y-m-d')) }}"
                           min="{{ today()->addDay()->format('Y-m-d') }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 transition bg-white"
                           required onchange="loadSlots()">
                </div>

                {{-- Slot Waktu (AJAX) --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Pilih Jam <span class="text-red-500">*</span></label>
                    <input type="hidden" name="jam_slot" id="jam-slot-input" value="{{ old('jam_slot') }}" required>

                    <div id="slots-container" class="flex flex-wrap gap-2">
                        <p class="text-gray-400 text-sm italic">Pilih dokter dan tanggal terlebih dahulu...</p>
                    </div>
                    @error('jam_slot')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
                </div>

                {{-- Keluhan --}}
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keluhan</label>
                    <textarea name="keluhan" rows="3"
                              placeholder="Tuliskan keluhan atau gejala yang dirasakan..."
                              class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 transition resize-none">{{ old('keluhan') }}</textarea>
                </div>

                {{-- ═══ KONTAK NOTIFIKASI (Tambahan Baru) ════════════════════ --}}
                <div class="mb-8 bg-teal-50 rounded-2xl p-5 border border-teal-100">
                    <div class="font-semibold text-gray-700 mb-1 flex items-center gap-2">
                        🔔 Konfirmasi dikirim ke:
                    </div>
                    <p class="text-xs text-gray-500 mb-4">Isi minimal salah satu agar kami bisa mengirim konfirmasi booking Anda.</p>

                    @error('kontak')
                    <div class="mb-3 bg-red-50 border border-red-200 rounded-xl px-3 py-2 text-sm text-red-600">
                        ⚠️ {{ $message }}
                    </div>
                    @enderror

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">📱 Nomor WhatsApp</label>
                            <input type="text" name="no_wa_pengirim" value="{{ old('no_wa_pengirim') }}"
                                   placeholder="08xxxxxxxxxx"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 transition bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">✉️ Email</label>
                            <input type="email" name="email_pengirim" value="{{ old('email_pengirim') }}"
                                   placeholder="nama@email.com"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 transition bg-white">
                        </div>
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-teal-700 hover:bg-teal-800 text-white font-bold py-4 rounded-xl transition text-sm tracking-wide shadow-lg shadow-teal-900/20">
                    Konfirmasi Booking →
                </button>
            </form>
        </div>
    </div>

    <script>
        // Fitur 1: Load Slot AJAX
        async function loadSlots() {
            const dokterId = document.getElementById('dokter-select').value;
            const tanggal  = document.getElementById('tanggal-input').value;
            const container = document.getElementById('slots-container');

            if (!dokterId || !tanggal) {
                container.innerHTML = '<p class="text-gray-400 text-sm italic">Pilih dokter dan tanggal terlebih dahulu...</p>';
                return;
            }

            container.innerHTML = '<div class="flex items-center gap-2 text-sm text-gray-400"><div class="w-4 h-4 border-2 border-teal-300 border-t-teal-600 rounded-full animate-spin"></div> Memuat slot...</div>';

            const res = await fetch(`/pegawai/api/slots?dokter_id=${dokterId}&tanggal=${tanggal}`);
            const { available, booked } = await res.json();

            if (available.length === 0) {
                container.innerHTML = '<p class="text-amber-600 text-sm">Semua slot sudah penuh untuk tanggal ini.</p>';
                return;
            }

            const allSlots = [...available, ...booked].sort();
            container.innerHTML = allSlots.map(slot => {
                const isBooked = booked.includes(slot);
                return `<button type="button"
                    class="slot-btn px-4 py-2 rounded-xl border text-sm font-medium
                           ${isBooked ? 'border-gray-200 text-gray-300 bg-gray-50 cursor-not-allowed' : 'border-teal-200 text-teal-700 hover:bg-teal-50 hover:border-teal-400'}"
                    onclick="${isBooked ? '' : `selectSlot('${slot}', this)`}"
                    ${isBooked ? 'disabled title="Slot sudah terisi"' : ''}>
                    ${slot}${isBooked ? ' ✗' : ''}
                </button>`;
            }).join('');

            // Restore old value if available
            const oldSlot = '{{ old("jam_slot") }}';
            if (oldSlot && available.includes(oldSlot)) {
                document.querySelectorAll('.slot-btn').forEach(btn => {
                    if (btn.textContent.trim() === oldSlot) selectSlot(oldSlot, btn);
                });
            }
        }

        function selectSlot(slot, btn) {
            document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('selected', 'bg-teal-600', 'text-white', 'border-teal-600'));
            btn.classList.add('selected', 'bg-teal-600', 'text-white', 'border-teal-600');
            document.getElementById('jam-slot-input').value = slot;
        }

        // Auto-load jika ada old values
        window.addEventListener('DOMContentLoaded', () => {
            if (document.getElementById('dokter-select').value && document.getElementById('tanggal-input').value) {
                loadSlots();
            }
        });

        // Fitur 2: Live NIP check
        let nipTimer;
        document.getElementById('nip-input').addEventListener('input', function() {
            clearTimeout(nipTimer);
            const nip = this.value.trim();
            const found    = document.getElementById('nip-found');
            const notFound = document.getElementById('nip-not-found');
            const spinner  = document.getElementById('nip-spinner');
            const ok       = document.getElementById('nip-check-ok');

            found.classList.add('hidden');
            notFound.classList.add('hidden');
            ok.classList.add('hidden');

            if (nip.length < 5) return;

            spinner.classList.remove('hidden');
            nipTimer = setTimeout(async () => {
                try {
                    const res  = await fetch(`/pegawai/api/cek-nip?nip=${encodeURIComponent(nip)}`);
                    const data = await res.json();
                    spinner.classList.add('hidden');

                    if (data.found) {
                        // Tampilkan nama & bidang di preview
                        document.getElementById('nip-nama').textContent = data.nama;
                        found.classList.remove('hidden');
                        ok.classList.remove('hidden');

                        // Auto-fill & kunci field Bidang jika data sudah ada di DB
                        const inputBidang = document.getElementById('bidang-input');
                        if (inputBidang) {
                            if (data.bidang) {
                                inputBidang.value = data.bidang;
                                inputBidang.setAttribute('readonly', true);
                                // PERUBAHAN: Warna background hijau muda & border hijau
                                inputBidang.classList.remove('border-gray-200');
                                inputBidang.classList.add('bg-green-50', 'text-green-700', 'border-green-300', 'cursor-not-allowed', 'font-semibold');
                            } else {
                                // Bidang belum ada di DB, biarkan bisa diisi
                                inputBidang.removeAttribute('readonly');
                                // Kembalikan ke warna default
                                inputBidang.classList.add('border-gray-200');
                                inputBidang.classList.remove('bg-green-50', 'text-green-700', 'border-green-300', 'cursor-not-allowed', 'font-semibold');
                            }
                        }

                        // Auto-select & kunci Jenis Kelamin jika data sudah ada di DB
                        if (data.jenis_kelamin) {
                            const radioJK = document.querySelector(`input[name="jenis_kelamin"][value="${data.jenis_kelamin}"]`);
                            if (radioJK) {
                                radioJK.checked = true;
                                radioJK.dispatchEvent(new Event('change'));
                            }
                        }

                    } else {
                        notFound.classList.remove('hidden');

                        // Reset field jika NIP tidak ditemukan
                        const inputBidang = document.getElementById('bidang-input');
                        if (inputBidang) {
                            inputBidang.value = '';
                            inputBidang.removeAttribute('readonly');
                            inputBidang.classList.add('border-gray-200');
                            inputBidang.classList.remove('bg-green-50', 'text-green-700', 'border-green-300', 'cursor-not-allowed', 'font-semibold');
                        }

                        document.querySelectorAll('input[name="jenis_kelamin"]').forEach(r => {
                            r.checked  = false;
                        });
                        document.querySelectorAll('.jk-option').forEach(el => {
                            el.classList.remove('border-teal-500', 'bg-teal-50', 'text-teal-700');
                            el.classList.add('border-gray-200', 'text-gray-600');
                        });
                    }
                } catch (e) {
                    spinner.classList.add('hidden');
                }
            }, 600);
        });

        // Fitur 3: Highlight opsi Jenis Kelamin
        document.querySelectorAll('input[name="jenis_kelamin"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Reset semua opsi ke warna dasar (abu-abu)
                document.querySelectorAll('.jk-option').forEach(el => {
                    el.classList.remove('border-teal-500', 'bg-teal-50', 'text-teal-700');
                    el.classList.add('border-gray-200', 'text-gray-600');
                });

                // Highlight opsi yang sedang dipilih (hijau toska)
                if (this.nextElementSibling) {
                    this.nextElementSibling.classList.remove('border-gray-200', 'text-gray-600');
                    this.nextElementSibling.classList.add('border-teal-500', 'bg-teal-50', 'text-teal-700');
                }
            });
        });

        // Restore state jika form mengalami error (menggunakan 'old' value)
        document.querySelectorAll('input[name="jenis_kelamin"]:checked').forEach(r => r.dispatchEvent(new Event('change')));
    </script>
</body>
</html>
