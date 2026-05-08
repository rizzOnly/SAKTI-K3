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

        /* UPLOAD ZONE CSS TAMBAHAN */
        .upload-zone {
            border: 2px dashed #d1d5db;
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            cursor: pointer;
            transition: all .2s;
            background: #fafafa;
            position: relative;
        }
        .upload-zone:hover,
        .upload-zone.dragover {
            border-color: #003D7C;
            background: #eff6ff;
        }
        .upload-zone input[type=file] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }
        .upload-zone.has-file {
            border-color: #16a34a;
            background: #f0fdf4;
        }
        .upload-preview {
            display: none;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            background: #f0fdf4;
            border: 1.5px solid #86efac;
            border-radius: 12px;
            margin-top: 10px;
        }
        .upload-preview.show { display: flex; }
        .upload-preview-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #dcfce7;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
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

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div class="bg-green-50 border border-green-200 rounded-2xl p-5 mb-6 flex items-start gap-3">
            <span class="text-green-500 text-xl flex-shrink-0">✓</span>
            <div>
                <div class="font-bold text-green-800">Pengajuan Berhasil!</div>
                <div class="text-green-700 text-sm mt-0.5"><?php echo e(session('success')); ?></div>
                <a href="<?php echo e(route('pegawai.apd')); ?>" class="text-sm text-green-700 underline mt-2 inline-block">Ajukan lagi</a>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
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

        
        <div id="tab-ambil" class="tab-content">
            <div class="bg-white rounded-2xl p-8 shadow-[0_0_0_1px_rgba(0,0,0,.06),0_4px_24px_rgba(0,61,124,.08)]">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-xl">📦</div>
                    <div>
                        <h2 class="font-bold text-gray-800 text-lg">Form Pengambilan APD</h2>
                        <p class="text-gray-400 text-xs">Untuk APD consumable / habis pakai</p>
                    </div>
                </div>

                <form method="POST" action="<?php echo e(route('pegawai.apd.ambil.store')); ?>" enctype="multipart/form-data" id="form-ambil">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="_form_type" value="ambil">
                    <?php echo $__env->make('pegawai._form-nid-field', ['formId' => 'ambil'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php echo $__env->make('pegawai._form-tanggal-field', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Item APD <span class="text-red-500">*</span></label>
                        <div id="ambil-items" class="space-y-3">
                            <div class="item-row flex gap-3">
                                <select name="items[0][apd_item_id]" class="flex-1 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white" required>
                                    <option value="">-- Pilih APD --</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $apdConsumable; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($item->id); ?>"><?php echo e($item->nama_barang); ?> (Stok: <?php echo e($item->stok); ?>)</option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                                <input type="number" name="items[0][jumlah]" value="1" min="1" class="w-24 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-center" required>
                            </div>
                        </div>
                        <button type="button" onclick="addItem('ambil-items', ambilOpts)" class="mt-3 text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">+ Tambah Item</button>
                    </div>

                    
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Berkas Permit / JSA <span class="text-red-500">*</span>
                        </label>
                        <p class="text-xs text-gray-400 mb-3">
                            Upload foto atau scan berkas Work Permit / JSA (Job Safety Analysis). Format: JPG, PNG, atau PDF. Maks 5 MB.
                        </p>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['berkas_permit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="mb-3 bg-red-50 border border-red-200 rounded-xl px-3 py-2 text-sm text-red-600">⚠️ <?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div class="upload-zone" id="ambil-upload-zone" ondragover="onDragOver(event, 'ambil')" ondragleave="onDragLeave('ambil')" ondrop="onDrop(event, 'ambil')">
                            <input type="file" name="berkas_permit" id="ambil-file-input" accept=".jpg,.jpeg,.png,.pdf" onchange="onFileSelected(this, 'ambil')" required>
                            <div id="ambil-upload-placeholder">
                                <div class="text-3xl mb-2">📎</div>
                                <div class="font-semibold text-gray-600 text-sm">Klik atau seret file ke sini</div>
                                <div class="text-xs text-gray-400 mt-1">JPG, PNG, PDF — Maks 5 MB</div>
                            </div>
                        </div>
                        <div class="upload-preview" id="ambil-upload-preview">
                            <div class="upload-preview-icon" id="ambil-preview-icon">📄</div>
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-green-800 text-sm truncate" id="ambil-file-name">—</div>
                                <div class="text-xs text-green-600" id="ambil-file-size">—</div>
                            </div>
                            <button type="button" onclick="clearFile('ambil')" class="text-gray-400 hover:text-red-500 transition text-xl leading-none flex-shrink-0">×</button>
                        </div>
                    </div>

                    <?php echo $__env->make('pegawai._form-catatan-field', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php echo $__env->make('pegawai._form-kontak-field', ['color' => 'blue'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <button type="submit" class="w-full bg-[#003D7C] hover:bg-blue-900 text-white font-bold py-4 rounded-xl transition text-sm tracking-wide shadow-lg shadow-blue-900/20">Kirim Pengajuan Ambil →</button>
                </form>
            </div>
        </div>

        
        <div id="tab-pinjam" class="tab-content">
            <div class="bg-white rounded-2xl p-8 shadow-[0_0_0_1px_rgba(0,0,0,.06),0_4px_24px_rgba(13,148,136,.1)]">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-teal-100 rounded-xl flex items-center justify-center text-xl">🔄</div>
                    <div>
                        <h2 class="font-bold text-gray-800 text-lg">Form Peminjaman APD</h2>
                        <p class="text-gray-400 text-xs">Untuk APD returnable / dikembalikan</p>
                    </div>
                </div>

                <form method="POST" action="<?php echo e(route('pegawai.apd.pinjam.store')); ?>" enctype="multipart/form-data" id="form-pinjam">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="_form_type" value="pinjam">
                    <?php echo $__env->make('pegawai._form-nid-field', ['formId' => 'pinjam'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php echo $__env->make('pegawai._form-tanggal-field', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Rencana Tanggal Kembali <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_kembali_rencana" value="<?php echo e(old('tanggal_kembali_rencana')); ?>" min="<?php echo e(today()->addDay()->format('Y-m-d')); ?>" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 transition" required>
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Item APD <span class="text-red-500">*</span></label>
                        <div id="pinjam-items" class="space-y-3">
                            <div class="item-row flex gap-3">
                                <select name="items[0][apd_item_id]" class="flex-1 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 bg-white" required>
                                    <option value="">-- Pilih APD --</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $apdReturnable; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($item->id); ?>"><?php echo e($item->nama_barang); ?> (Stok: <?php echo e($item->stok); ?>)</option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                                <input type="number" name="items[0][jumlah]" value="1" min="1" class="w-24 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 text-center" required>
                            </div>
                        </div>
                        <button type="button" onclick="addItem('pinjam-items', pinjamOpts)" class="mt-3 text-sm text-teal-600 hover:text-teal-800 font-medium flex items-center gap-1">+ Tambah Item</button>
                    </div>

                    
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Berkas JSA (Job Safety Analysis) <span class="text-red-500">*</span>
                        </label>
                        <p class="text-xs text-gray-400 mb-3">
                            Upload foto atau scan berkas JSA sebelum meminjam APD. Format: JPG, PNG, atau PDF. Maks 5 MB.
                        </p>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['berkas_jsa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="mb-3 bg-red-50 border border-red-200 rounded-xl px-3 py-2 text-sm text-red-600">⚠️ <?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div class="upload-zone" id="pinjam-upload-zone" ondragover="onDragOver(event, 'pinjam')" ondragleave="onDragLeave('pinjam')" ondrop="onDrop(event, 'pinjam')">
                            <input type="file" name="berkas_jsa" id="pinjam-file-input" accept=".jpg,.jpeg,.png,.pdf" onchange="onFileSelected(this, 'pinjam')" required>
                            <div id="pinjam-upload-placeholder">
                                <div class="text-3xl mb-2">📎</div>
                                <div class="font-semibold text-gray-600 text-sm">Klik atau seret file ke sini</div>
                                <div class="text-xs text-gray-400 mt-1">JPG, PNG, PDF — Maks 5 MB</div>
                            </div>
                        </div>
                        <div class="upload-preview" id="pinjam-upload-preview">
                            <div class="upload-preview-icon" id="pinjam-preview-icon">📄</div>
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-green-800 text-sm truncate" id="pinjam-file-name">—</div>
                                <div class="text-xs text-green-600" id="pinjam-file-size">—</div>
                            </div>
                            <button type="button" onclick="clearFile('pinjam')" class="text-gray-400 hover:text-red-500 transition text-xl leading-none flex-shrink-0">×</button>
                        </div>
                    </div>

                    <?php echo $__env->make('pegawai._form-catatan-field', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php echo $__env->make('pegawai._form-kontak-field', ['color' => 'teal'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <button type="submit" class="w-full bg-teal-700 hover:bg-teal-800 text-white font-bold py-4 rounded-xl transition text-sm tracking-wide shadow-lg shadow-teal-900/20">Kirim Pengajuan Pinjam →</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const ambilOpts = `<?php $__currentLoopData = $apdConsumable; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($i->id); ?>"><?php echo e($i->nama_barang); ?> (Stok: <?php echo e($i->stok); ?>)</option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>`;
        const pinjamOpts = `<?php $__currentLoopData = $apdReturnable; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($i->id); ?>"><?php echo e($i->nama_barang); ?> (Stok: <?php echo e($i->stok); ?>)</option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>`;
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

        // Live NID Check (Disesuaikan untuk Multi-Tab)
        ['ambil', 'pinjam'].forEach(formId => {
            let nipTimer;
            const input = document.getElementById(`nid-input-${formId}`);
            if(!input) return;

            input.addEventListener('input', function() {
                clearTimeout(nipTimer);
                const nid = this.value.trim();
                document.getElementById(`nid-found-${formId}`).classList.add('hidden');
                document.getElementById(`nid-not-found-${formId}`).classList.add('hidden');
                document.getElementById(`nid-ok-${formId}`).classList.add('hidden');

                if (nid.length < 5) return;

                const spinner = document.getElementById(`nid-spinner-${formId}`);
                spinner.classList.remove('hidden');

                nipTimer = setTimeout(async () => {
                    try {
                        const res = await fetch(`/pegawai/api/cek-nid?nid=${encodeURIComponent(nid)}`);
                        const data = await res.json();
                        spinner.classList.add('hidden');
                        if (data.found) {
                            document.getElementById(`nid-nama-${formId}`).textContent = data.nama;
                            document.getElementById(`nid-bidang-${formId}`).textContent = data.bidang ? `– ${data.bidang}` : '';
                            document.getElementById(`nid-found-${formId}`).classList.remove('hidden');
                            document.getElementById(`nid-ok-${formId}`).classList.remove('hidden');
                        } else {
                            document.getElementById(`nid-not-found-${formId}`).classList.remove('hidden');
                        }
                    } catch (e) {
                        spinner.classList.add('hidden');
                    }
                }, 600);
            });
        });

        // ── FILE UPLOAD HANDLERS (TAMBAHAN) ──────────────────────────────────
        function formatBytes(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        }

        function onFileSelected(input, type) {
            const file = input.files[0];
            if (!file) return;

            const zone  = document.getElementById(type + '-upload-zone');
            const preview = document.getElementById(type + '-upload-preview');
            const icon  = document.getElementById(type + '-preview-icon');
            const nameEl  = document.getElementById(type + '-file-name');
            const sizeEl  = document.getElementById(type + '-file-size');
            const placeholder = document.getElementById(type + '-upload-placeholder');

            // Validasi ukuran (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 5 MB.');
                input.value = '';
                return;
            }

            // Update UI
            zone.classList.add('has-file');
            placeholder.innerHTML = `<div class="text-green-600 text-sm font-semibold">✓ File dipilih</div>`;

            icon.textContent  = file.type === 'application/pdf' ? '📄' : '🖼️';
            nameEl.textContent = file.name;
            sizeEl.textContent = formatBytes(file.size);
            preview.classList.add('show');

            // Tampilkan preview gambar jika bukan PDF
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = e => {
                    icon.innerHTML = `<img src="${e.target.result}" class="w-10 h-10 object-cover rounded-lg" alt="preview">`;
                };
                reader.readAsDataURL(file);
            }
        }

        function clearFile(type) {
            const input   = document.getElementById(type + '-file-input');
            const zone    = document.getElementById(type + '-upload-zone');
            const preview = document.getElementById(type + '-upload-preview');
            const icon    = document.getElementById(type + '-preview-icon');
            const placeholder = document.getElementById(type + '-upload-placeholder');

            input.value = '';
            zone.classList.remove('has-file');
            preview.classList.remove('show');
            icon.textContent = '📄';
            placeholder.innerHTML = `
                <div class="text-3xl mb-2">📎</div>
                <div class="font-semibold text-gray-600 text-sm">Klik atau seret file ke sini</div>
                <div class="text-xs text-gray-400 mt-1">JPG, PNG, PDF — Maks 5 MB</div>`;
        }

        function onDragOver(e, type) {
            e.preventDefault();
            document.getElementById(type + '-upload-zone').classList.add('dragover');
        }

        function onDragLeave(type) {
            document.getElementById(type + '-upload-zone').classList.remove('dragover');
        }

        function onDrop(e, type) {
            e.preventDefault();
            onDragLeave(type);
            const input = document.getElementById(type + '-file-input');
            const dt = e.dataTransfer;
            if (dt.files.length) {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(dt.files[0]);
                input.files = dataTransfer.files;
                onFileSelected(input, type);
            }
        }

        // Tab otomatis terbuka jika ada validasi error
        <?php if($errors->any() && old('_form_type')): ?>
            switchTab("<?php echo e(old('_form_type')); ?>");
        <?php endif; ?>
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\k3-pltgu\resources\views/pegawai/form-apd.blade.php ENDPATH**/ ?>