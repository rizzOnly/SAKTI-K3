<div class="mb-5">
    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Pengajuan <span class="text-red-500">*</span></label>
    <input type="date" name="tanggal_pengajuan"
           value="{{ old('tanggal_pengajuan', today()->format('Y-m-d')) }}"
           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
</div>
