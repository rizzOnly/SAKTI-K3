<div class="mb-5">
    <label class="block text-sm font-semibold text-gray-700 mb-2">NIP Pegawai <span class="text-red-500">*</span></label>
    <div class="relative">
        <input type="text" id="nip-input-{{ $formId }}" name="nip"
               value="{{ old('nip') }}"
               placeholder="Masukkan NIP Anda"
               class="w-full border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition pr-10 @error('nip') border-red-400 bg-red-50 @else border-gray-200 @enderror"
               required autocomplete="off">
        <div id="nip-spinner-{{ $formId }}" class="hidden absolute right-3 top-3.5 w-4 h-4 border-2 border-blue-300 border-t-blue-600 rounded-full animate-spin"></div>
        <div id="nip-ok-{{ $formId }}" class="hidden absolute right-3 top-3 text-green-500 text-lg">✓</div>
    </div>
    @error('nip')
    <div class="mt-2 bg-red-50 border border-red-200 rounded-xl p-3 flex items-start gap-2">
        <span class="text-red-500 flex-shrink-0">⚠️</span>
        <div>
            <p class="text-red-700 text-sm font-medium">{{ $message }}</p>
            <p class="text-red-500 text-xs mt-1">Hubungi Admin K3 di pos K3 atau ext. 101.</p>
        </div>
    </div>
    @enderror
    <div id="nip-not-found-{{ $formId }}" class="hidden mt-2 bg-amber-50 border border-amber-200 rounded-xl p-3 flex items-start gap-2">
        <span class="text-amber-500 flex-shrink-0">⚠️</span>
        <div>
            <p class="text-amber-700 text-sm font-medium">NIP tidak ditemukan.</p>
            <p class="text-amber-500 text-xs mt-1">Hubungi Admin K3 untuk mendaftar.</p>
        </div>
    </div>
    <div id="nip-found-{{ $formId }}" class="hidden mt-2 bg-green-50 border border-green-200 rounded-xl px-3 py-2 text-sm text-green-700">
        👤 <span id="nip-nama-{{ $formId }}" class="font-semibold"></span>
        <span id="nip-bidang-{{ $formId }}" class="text-green-500 ml-2 text-xs"></span>
    </div>
</div>
