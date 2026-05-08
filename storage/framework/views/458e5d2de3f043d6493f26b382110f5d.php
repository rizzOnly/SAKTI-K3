<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Fit to Work – K3 PLN Sengkang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-cyan-50 to-slate-100">

    <div class="bg-[#003D7C] text-white py-4 px-6 flex items-center gap-4 shadow-lg sticky top-0 z-10">
        <a href="/" class="text-blue-200 hover:text-white transition text-sm">← Beranda</a>
        <div class="h-5 w-px bg-blue-500"></div>
        <div>
            <div class="font-bold text-lg">Fit to Work – Pekerjaan Risiko Tinggi</div>
            <div class="text-blue-200 text-xs">PT PLN Nusantara Power – Unit Sengkang</div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-10 pb-16">

        
        <div class="bg-red-600 text-white rounded-2xl p-5 mb-8 flex gap-4 items-start shadow-lg">
            <span class="text-3xl flex-shrink-0">⚠️</span>
            <div>
                <div class="font-bold text-lg mb-1">Pemeriksaan Wajib Sebelum Bekerja</div>
                <div class="text-red-100 text-sm leading-relaxed">
                    Setiap pekerja yang akan melakukan <strong>pekerjaan risiko tinggi</strong> wajib
                    mengisi form ini dan menjalani pemeriksaan dokter di Klinik Unit untuk mendapatkan
                    pernyataan <strong>Fit to Work</strong>.
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-8 shadow-[0_0_0_1px_rgba(0,0,0,.06),0_4px_24px_rgba(0,61,124,.08)]">

            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center text-2xl">🏥</div>
                <div>
                    <h1 class="font-bold text-gray-800 text-xl">Form Fit to Work</h1>
                    <p class="text-gray-400 text-sm">Isi data dengan lengkap dan benar.</p>
                </div>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
            <div class="mb-5 bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-600">
                <ul class="list-disc list-inside space-y-1">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($err); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </ul>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <form method="POST" action="<?php echo e(route('fit-to-work.store')); ?>">
                <?php echo csrf_field(); ?>

                
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Pekerja <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="tipe-btn flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition
                                      <?php echo e(old('tipe', '') === 'internal' ? 'border-blue-600 bg-blue-50' : 'border-gray-200 hover:border-blue-300'); ?>">
                            <input type="radio" name="tipe" value="internal" class="hidden" onchange="onTipeChange()"
                                   <?php echo e(old('tipe') === 'internal' ? 'checked' : ''); ?>>
                            <span class="text-2xl">👷</span>
                            <div>
                                <div class="font-semibold text-gray-800 text-sm">Pegawai Internal</div>
                                <div class="text-gray-400 text-xs">PLN Nusantara Power</div>
                            </div>
                        </label>
                        <label class="tipe-btn flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition
                                      <?php echo e(old('tipe', '') === 'vendor' ? 'border-amber-500 bg-amber-50' : 'border-gray-200 hover:border-amber-300'); ?>">
                            <input type="radio" name="tipe" value="vendor" class="hidden" onchange="onTipeChange()"
                                   <?php echo e(old('tipe') === 'vendor' ? 'checked' : ''); ?>>
                            <span class="text-2xl">🏢</span>
                            <div>
                                <div class="font-semibold text-gray-800 text-sm">Vendor / Kontraktor</div>
                                <div class="text-gray-400 text-xs">Perusahaan luar</div>
                            </div>
                        </label>
                    </div>
                </div>

                
                <div class="bg-gray-50 rounded-xl p-4 mb-5 border border-gray-100 space-y-4">
                    <div class="font-semibold text-gray-700 text-sm uppercase tracking-wide">Data Diri</div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="<?php echo e(old('nama')); ?>"
                               placeholder="Nama sesuai identitas"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white" required>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <div class="flex gap-3">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="jenis_kelamin" value="L" <?php echo e(old('jenis_kelamin') === 'L' ? 'checked' : ''); ?> required>
                                <span class="text-sm text-gray-700">Laki-laki</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="jenis_kelamin" value="P" <?php echo e(old('jenis_kelamin') === 'P' ? 'checked' : ''); ?>>
                                <span class="text-sm text-gray-700">Perempuan</span>
                            </label>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['jenis_kelamin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    
                    <div id="field-perusahaan" class="<?php echo e(old('tipe') === 'vendor' ? '' : 'hidden'); ?>">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Perusahaan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_perusahaan" value="<?php echo e(old('nama_perusahaan')); ?>"
                               placeholder="Contoh: CV Marezho"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 bg-white">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nama_perusahaan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">No. WhatsApp</label>
                        <input type="text" name="no_wa" value="<?php echo e(old('no_wa')); ?>"
                               placeholder="08xxxxxxxxxx"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                    </div>
                </div>

                
                <div class="bg-red-50 rounded-xl p-4 mb-6 border border-red-100 space-y-4">
                    <div class="font-semibold text-gray-700 text-sm uppercase tracking-wide">Data Pekerjaan Risiko Tinggi</div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama / Jenis Pekerjaan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_pekerjaan" value="<?php echo e(old('nama_pekerjaan')); ?>"
                               placeholder="Contoh: Pekerjaan di ketinggian, confined space, listrik tegangan tinggi"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 bg-white" required>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nama_pekerjaan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Mulai <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_mulai" value="<?php echo e(old('tanggal_mulai')); ?>"
                                   min="<?php echo e(today()->format('Y-m-d')); ?>"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 bg-white" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Selesai <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_selesai" value="<?php echo e(old('tanggal_selesai')); ?>"
                                   min="<?php echo e(today()->format('Y-m-d')); ?>"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 bg-white" required>
                        </div>
                    </div>
                </div>

                
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 flex gap-3">
                    <span class="text-2xl flex-shrink-0">ℹ️</span>
                    <div>
                        <div class="font-semibold text-blue-800 text-sm">Setelah Submit</div>
                        <div class="text-blue-600 text-xs mt-1 leading-relaxed">
                            Dokter klinik akan mendapat notifikasi dan melakukan pemeriksaan.
                            Harap segera datang ke <strong>Klinik Unit PLN Sengkang</strong> untuk pemeriksaan fisik.
                        </div>
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-xl transition text-sm tracking-wide shadow-lg shadow-red-900/20">
                    Submit & Daftarkan Pemeriksaan →
                </button>
            </form>
        </div>
    </div>

    <script>
        function onTipeChange() {
            const selected = document.querySelector('input[name="tipe"]:checked')?.value;
            const fieldPerusahaan = document.getElementById('field-perusahaan');

            // Update style tombol tipe
            document.querySelectorAll('.tipe-btn').forEach(btn => {
                const radio = btn.querySelector('input[type="radio"]');
                const isVendor = radio.value === 'vendor';
                const isChecked = radio.checked;
                btn.classList.remove('border-blue-600','bg-blue-50','border-amber-500','bg-amber-50','border-gray-200');

                if (isChecked) {
                    btn.classList.add(isVendor ? 'border-amber-500' : 'border-blue-600');
                    btn.classList.add(isVendor ? 'bg-amber-50' : 'bg-blue-50');
                } else {
                    btn.classList.add('border-gray-200');
                }
            });

            // Tampilkan field perusahaan hanya untuk vendor
            if (selected === 'vendor') {
                fieldPerusahaan.classList.remove('hidden');
                fieldPerusahaan.querySelector('input').required = true;
            } else {
                fieldPerusahaan.classList.add('hidden');
                fieldPerusahaan.querySelector('input').required = false;
            }
        }

        // Init saat load
        document.addEventListener('DOMContentLoaded', onTipeChange);
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\k3-pltgu\resources\views/fit-to-work/form.blade.php ENDPATH**/ ?>