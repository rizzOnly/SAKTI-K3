<div class="mb-5">
    <label class="block text-sm font-semibold text-gray-700 mb-2">NID Pegawai <span class="text-red-500">*</span></label>
    <div class="relative">
        <input type="text" id="nid-input-<?php echo e($formId); ?>" name="nid"
               value="<?php echo e(old('nid')); ?>"
               placeholder="Masukkan NID Anda"
               class="w-full border rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition pr-10 <?php $__errorArgs = ['nid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 bg-red-50 <?php else: ?> border-gray-200 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
               required autocomplete="off">
        <div id="nid-spinner-<?php echo e($formId); ?>" class="hidden absolute right-3 top-3.5 w-4 h-4 border-2 border-blue-300 border-t-blue-600 rounded-full animate-spin"></div>
        <div id="nid-ok-<?php echo e($formId); ?>" class="hidden absolute right-3 top-3 text-green-500 text-lg">✓</div>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
    <div class="mt-2 bg-red-50 border border-red-200 rounded-xl p-3 flex items-start gap-2">
        <span class="text-red-500 flex-shrink-0">⚠️</span>
        <div>
            <p class="text-red-700 text-sm font-medium"><?php echo e($message); ?></p>
            <p class="text-red-500 text-xs mt-1">Hubungi Admin K3 di pos K3 atau ext. 101.</p>
        </div>
    </div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <div id="nid-not-found-<?php echo e($formId); ?>" class="hidden mt-2 bg-amber-50 border border-amber-200 rounded-xl p-3 flex items-start gap-2">
        <span class="text-amber-500 flex-shrink-0">⚠️</span>
        <div>
            <p class="text-amber-700 text-sm font-medium">NID tidak ditemukan.</p>
            <p class="text-amber-500 text-xs mt-1">Hubungi Admin K3 untuk mendaftar.</p>
        </div>
    </div>
    <div id="nid-found-<?php echo e($formId); ?>" class="hidden mt-2 bg-green-50 border border-green-200 rounded-xl px-3 py-2 text-sm text-green-700">
        👤 <span id="nid-nama-<?php echo e($formId); ?>" class="font-semibold"></span>
        <span id="nid-bidang-<?php echo e($formId); ?>" class="text-green-500 ml-2 text-xs"></span>
    </div>
</div>
<?php /**PATH C:\laragon\www\k3-pltgu\resources\views/pegawai/_form-nid-field.blade.php ENDPATH**/ ?>