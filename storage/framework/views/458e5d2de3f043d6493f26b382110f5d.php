<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Fit to Work – D-SAVE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .tipe-label {
            display:flex;
            align-items:center;
            gap:12px;
            padding:14px 16px;
            border:2px solid #e5e7eb;
            border-radius:14px;
            cursor:pointer;
            transition:all .2s;
        }
        .tipe-label.active-internal { border-color:#003D7C; background:#eff6ff; }
        .tipe-label.active-vendor   { border-color:#d97706; background:#fffbeb; }
        .tipe-label input { display:none; }
        select {
            appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat:no-repeat;
            background-position:right 12px center;
            background-size:16px;
            padding-right:36px;
        }

        /* Mobile optimizations */
        @media (max-width: 640px) {
            .tipe-label {
                padding: 10px 8px;
                gap: 8px;
                /* Keep horizontal on mobile */
                flex-direction: row;
                text-align: left;
            }
            .tipe-label .text-2xl {
                font-size: 1.25rem;
            }
            .tipe-label .text-sm {
                font-size: 0.75rem;
            }
            .tipe-label .text-xs {
                font-size: 0.65rem;
            }
            /* Keep pekerja fields side-by-side */
            .pekerja-row .grid-cols-2 {
                grid-template-columns: 1fr 1fr !important;
                gap: 8px;
            }
            .pekerja-row .col-span-2 {
                grid-column: span 1 !important;
            }
            .bg-white.rounded-2xl {
                padding: 1rem !important;
            }
            .mb-6 {
                margin-bottom: 1rem !important;
            }
            button[type="submit"] {
                padding: 0.75rem 1rem !important;
                font-size: 0.875rem !important;
            }
            input[type="date"],
            input[type="text"],
            input[type="email"],
            textarea,
            select {
                font-size: 16px !important;
            }
        }
            .tipe-label .text-2xl {
                font-size: 1.25rem;
            }
            .tipe-label .text-sm {
                font-size: 0.75rem;
            }
            .tipe-label .text-xs {
                font-size: 0.65rem;
            }
            .grid-cols-2 {
                grid-template-columns: 1fr !important;
            }
            .bg-white.rounded-2xl {
                padding: 1rem !important;
            }
            .mb-6 {
                margin-bottom: 1rem !important;
            }
            button[type="submit"] {
                padding: 0.75rem 1rem !important;
                font-size: 0.875rem !important;
            }
            input[type="date"],
            input[type="text"],
            input[type="email"],
            textarea,
            select {
                font-size: 16px !important;
            }
        }
            .tipe-label .text-2xl {
                font-size: 1.5rem;
            }
            .grid-cols-2 {
                grid-template-columns: 1fr !important;
            }
            .bg-white.rounded-2xl {
                padding: 1rem !important;
            }
            .mb-6 {
                margin-bottom: 1rem !important;
            }
            button[type="submit"] {
                padding: 0.75rem 1rem !important;
                font-size: 0.875rem !important;
            }
            input[type="date"],
            input[type="text"],
            input[type="email"],
            textarea,
            select {
                font-size: 16px !important; /* Prevents zoom on iOS */
            }
        }
    </style>
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
                    <p class="text-gray-400 text-sm">Satu submission bisa untuk beberapa pekerja.</p>
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
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Tipe Pekerja <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="tipe-label <?php echo e(old('tipe') === 'internal' ? 'active-internal' : ''); ?>" id="label-internal">
                            <input type="radio" name="tipe" value="internal"
                                   onchange="onTipeChange()"
                                   <?php echo e(old('tipe') === 'internal' ? 'checked' : ''); ?>>
                            <span class="text-2xl">👷</span>
                            <div>
                                <div class="font-semibold text-gray-800 text-sm">Pegawai Internal</div>
                                <div class="text-gray-400 text-xs">PLN Nusantara Power</div>
                            </div>
                        </label>
                        <label class="tipe-label <?php echo e(old('tipe') === 'vendor' ? 'active-vendor' : ''); ?>" id="label-vendor">
                            <input type="radio" name="tipe" value="vendor"
                                   onchange="onTipeChange()"
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
                    <div class="font-semibold text-gray-700 text-sm uppercase tracking-wide">Informasi Pekerjaan</div>

                    
                    <div id="field-perusahaan" class="<?php echo e(old('tipe') === 'vendor' ? '' : 'hidden'); ?>">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Nama Perusahaan / CV <span class="text-red-500">*</span>
                        </label>
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
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Nama / Jenis Pekerjaan Risiko Tinggi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_pekerjaan" value="<?php echo e(old('nama_pekerjaan')); ?>"
                               placeholder="Contoh: Pekerjaan di ketinggian, confined space"
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
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Tanggal Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_mulai" value="<?php echo e(old('tanggal_mulai')); ?>"
                                   min="<?php echo e(today()->format('Y-m-d')); ?>"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 bg-white" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Tanggal Selesai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_selesai" value="<?php echo e(old('tanggal_selesai')); ?>"
                                   min="<?php echo e(today()->format('Y-m-d')); ?>"
                                   class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 bg-white" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">No. WhatsApp PIC</label>
                        <input type="text" name="no_wa" value="<?php echo e(old('no_wa')); ?>"
                               placeholder="08xxxxxxxxxx"
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                    </div>
                </div>

                
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-1">
                        <div class="font-semibold text-gray-700">
                            Daftar Pekerja <span class="text-red-500">*</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mb-4">
                        Tambahkan semua pekerja dari perusahaan/CV yang sama. Setiap pekerja akan diperiksa secara individual oleh dokter.
                    </p>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['pekerjas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mb-2"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div id="pekerja-list" class="space-y-3">
                        <?php $oldPekerjas = old('pekerjas', [[]]); ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $oldPekerjas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="pekerja-row bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                    Pekerja #<span class="row-num"><?php echo e($i + 1); ?></span>
                                </span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($i > 0): ?>
                                <button type="button" onclick="removeRow(this)"
                                        class="text-gray-300 hover:text-red-400 transition text-xl leading-none">×</button>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Lengkap *</label>
                                    <input type="text" name="pekerjas[<?php echo e($i); ?>][nama]"
                                           value="<?php echo e($p['nama'] ?? ''); ?>"
                                           placeholder="Nama sesuai identitas"
                                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 bg-white" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Jenis Kelamin *</label>
                                    <select name="pekerjas[<?php echo e($i); ?>][jenis_kelamin]"
                                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 bg-white" required>
                                        <option value="">— Pilih —</option>
                                        <option value="L" <?php echo e(($p['jenis_kelamin'] ?? '') === 'L' ? 'selected' : ''); ?>>Laki-laki</option>
                                        <option value="P" <?php echo e(($p['jenis_kelamin'] ?? '') === 'P' ? 'selected' : ''); ?>>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <button type="button" onclick="addPekerja()"
                            class="mt-3 flex items-center gap-2 text-sm text-red-600 hover:text-red-800 font-semibold transition">
                        <span class="text-lg">+</span> Tambah Pekerja
                    </button>
                </div>

                
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 flex gap-3">
                    <span class="text-2xl flex-shrink-0">ℹ️</span>
                    <div>
                        <div class="font-semibold text-blue-800 text-sm">Setelah Submit</div>
                        <div class="text-blue-600 text-xs mt-1 leading-relaxed">
                            Dokter klinik akan mendapat notifikasi untuk setiap pekerja.
                            Harap segera datang ke <strong>Klinik Unit PLN Sengkang</strong>.
                            Status <strong>Fit to Work</strong> tiap pekerja akan tampil di landing page setelah diperiksa.
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
        let rowCount = <?php echo e(count($oldPekerjas ?? [1])); ?>;

        function onTipeChange() {
            const tipe = document.querySelector('input[name="tipe"]:checked')?.value;
            const fp   = document.getElementById('field-perusahaan');
            const li   = document.getElementById('label-internal');
            const lv   = document.getElementById('label-vendor');

            li.classList.remove('active-internal');
            lv.classList.remove('active-vendor');

            if (tipe === 'vendor') {
                lv.classList.add('active-vendor');
                fp.classList.remove('hidden');
                fp.querySelector('input').required = true;
            } else {
                li.classList.add('active-internal');
                fp.classList.add('hidden');
                fp.querySelector('input').required = false;
            }
        }

        function addPekerja() {
            const idx = rowCount++;
            const div = document.createElement('div');
            div.className = 'pekerja-row bg-gray-50 rounded-xl p-4 border border-gray-100';
            div.innerHTML = `
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pekerja #${idx + 1}</span>
                    <button type="button" onclick="removeRow(this)" class="text-gray-300 hover:text-red-400 transition text-xl leading-none">×</button>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Lengkap *</label>
                        <input type="text" name="pekerjas[${idx}][nama]"
                               placeholder="Nama sesuai identitas"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 bg-white" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Jenis Kelamin *</label>
                        <select name="pekerjas[${idx}][jenis_kelamin]"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 bg-white" required>
                            <option value="">— Pilih —</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>`;
            document.getElementById('pekerja-list').appendChild(div);
        }

        function removeRow(btn) {
            const rows = document.querySelectorAll('.pekerja-row');
            if (rows.length <= 1) return;
            btn.closest('.pekerja-row').remove();
        }

        // Init
        document.addEventListener('DOMContentLoaded', onTipeChange);
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\k3-pltgu\resources\views/fit-to-work/form.blade.php ENDPATH**/ ?>