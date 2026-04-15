@php $c = $color ?? 'blue'; @endphp
<div class="mb-8 bg-{{ $c }}-50 rounded-2xl p-5 border border-{{ $c }}-100">
    <div class="font-semibold text-gray-700 mb-1">🔔 Konfirmasi dikirim ke:</div>
    <p class="text-xs text-gray-500 mb-4">Isi minimal salah satu kontak di bawah.</p>
    @error('kontak')
    <div class="mb-3 bg-red-50 border border-red-200 rounded-xl px-3 py-2 text-sm text-red-600">⚠️ {{ $message }}</div>
    @enderror
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">📱 Nomor WhatsApp</label>
            <input type="text" name="no_wa_pengirim" value="{{ old('no_wa_pengirim') }}" placeholder="08xxxxxxxxxx"
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-{{ $c }}-400 bg-white">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">✉️ Email</label>
            <input type="email" name="email_pengirim" value="{{ old('email_pengirim') }}" placeholder="nama@email.com"
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-{{ $c }}-400 bg-white">
        </div>
    </div>
</div>
