<?php $c = $color ?? 'blue'; ?>
<div class="mb-8 bg-<?php echo e($c); ?>-50 rounded-2xl p-5 border border-<?php echo e($c); ?>-100">
    <div class="font-semibold text-gray-700 mb-1">🔔 Konfirmasi dikirim ke:</div>
    <p class="text-xs text-gray-500 mb-4">Isi minimal salah satu kontak di bawah.</p>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['kontak'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
    <div class="mb-3 bg-red-50 border border-red-200 rounded-xl px-3 py-2 text-sm text-red-600">⚠️ <?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">📱 Nomor WhatsApp</label>
            <input type="text" name="no_wa_pengirim" value="<?php echo e(old('no_wa_pengirim')); ?>" placeholder="08xxxxxxxxxx"
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-<?php echo e($c); ?>-400 bg-white">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1.5">✉️ Email</label>
            <input type="email" name="email_pengirim" value="<?php echo e(old('email_pengirim')); ?>" placeholder="nama@email.com"
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-<?php echo e($c); ?>-400 bg-white">
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\k3-pltgu\resources\views/pegawai/_form-kontak-field.blade.php ENDPATH**/ ?>