<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – D-SAVE PLN NP UP Sengkang</title>
    <link rel="icon" href="<?php echo e(asset('images/logo-sakti.png')); ?>" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-pattern {
            background-color: #003D7C;
            background-image:
                radial-gradient(circle at 20% 80%, rgba(255,199,44,.12) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255,199,44,.08) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(255,255,255,.03) 0%, transparent 70%);
        }
        .input-field {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            font-family: inherit;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
            color: #111827;
        }
        .input-field:focus {
            border-color: #003D7C;
            box-shadow: 0 0 0 3px rgba(0,61,124,.1);
        }
        .input-field.error {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239,68,68,.1);
        }
        .btn-login {
            width: 100%;
            padding: 13px;
            background: #003D7C;
            color: #fff;
            font-weight: 700;
            font-size: 15px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            transition: background .2s, transform .1s;
            font-family: inherit;
            letter-spacing: .3px;
        }
        .btn-login:hover  { background: #002d5c; }
        .btn-login:active { transform: scale(.99); }
        .btn-login:disabled {
            opacity: .6;
            cursor: not-allowed;
            transform: none;
        }
    </style>
</head>
<body class="min-h-screen bg-pattern flex items-center justify-center p-4">

    <div class="w-full max-w-sm">

        
        <div class="text-center mb-8">
            
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-2xl shadow-xl mb-4 overflow-hidden">
                <img src="<?php echo e(asset('images/logo-sakti.png')); ?>"
                     alt="D-SAVE"
                     class="w-12 h-12 object-contain">
            </div>
            <h1 class="text-white font-bold text-2xl">D-SAVE</h1>
            <p class="text-blue-200 text-sm mt-1">
                Platform Terpadu K3 & Manajemen APD<br>
                <span class="text-blue-300 text-xs">PT PLN Nusantara Power — UP Sengkang</span>
            </p>
        </div>

        
        <div class="bg-white rounded-2xl p-8 shadow-2xl">

            <h2 class="text-gray-800 font-bold text-xl mb-1">Masuk ke Panel</h2>
            <p class="text-gray-400 text-sm mb-6">
                Masukkan kredensial akun Anda. Sistem akan mengarahkan ke panel sesuai role.
            </p>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
            <div class="mb-5 bg-green-50 border border-green-200 rounded-xl px-4 py-3 flex items-center gap-2 text-sm text-green-700">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <?php echo e(session('success')); ?>

            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->has('email')): ?>
            <div class="mb-5 bg-red-50 border border-red-200 rounded-xl px-4 py-3 flex items-start gap-2 text-sm text-red-700">
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/>
                </svg>
                <?php echo e($errors->first('email')); ?>

            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <form method="POST" action="<?php echo e(route('login')); ?>" id="login-form">
                <?php echo csrf_field(); ?>

                
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Email
                    </label>
                    <input type="email"
                           name="email"
                           value="<?php echo e(old('email')); ?>"
                           placeholder="nama@email.com"
                           autocomplete="email"
                           autofocus
                           class="input-field <?php echo e($errors->has('email') ? 'error' : ''); ?>"
                           required>
                </div>

                
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Password
                    </label>
                    <div class="relative">
                        <input type="password"
                               name="password"
                               id="password-input"
                               placeholder="••••••••"
                               autocomplete="current-password"
                               class="input-field <?php echo e($errors->has('email') ? 'error' : ''); ?>"
                               required>
                        
                        <button type="button"
                                onclick="togglePassword()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition"
                                tabindex="-1">
                            <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <svg id="eye-off-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                
                <div class="flex items-center mb-6">
                    <input type="checkbox"
                           name="remember"
                           id="remember"
                           class="w-4 h-4 text-blue-600 rounded border-gray-300 cursor-pointer">
                    <label for="remember" class="ml-2 text-sm text-gray-600 cursor-pointer">
                        Ingat saya
                    </label>
                </div>

                <button type="submit" class="btn-login" id="btn-submit">
                    Masuk
                </button>
            </form>

            
            <div class="mt-6 pt-5 border-t border-gray-100">
                <p class="text-xs text-gray-400 text-center mb-3">Sistem akan otomatis mengarahkan ke:</p>
                <div class="grid grid-cols-2 gap-2">
                    <div class="bg-blue-50 rounded-xl p-3 text-center">
                        <div class="text-blue-600 font-bold text-xs">⚙️ Admin K3</div>
                        <div class="text-blue-400 text-[11px] mt-0.5">Panel APD & Laporan</div>
                    </div>
                    <div class="bg-teal-50 rounded-xl p-3 text-center">
                        <div class="text-teal-600 font-bold text-xs">🏥 Dokter / Perawat</div>
                        <div class="text-teal-400 text-[11px] mt-0.5">Panel Klinik</div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="text-center mt-6">
            <a href="/" class="text-blue-200 hover:text-white text-sm transition flex items-center justify-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Beranda
            </a>
        </div>

        <p class="text-center text-blue-400 text-xs mt-4">
            © <?php echo e(date('Y')); ?> PT PLN Nusantara Power — UP Sengkang
        </p>
    </div>

    <script>
        // Show/hide password
        function togglePassword() {
            const input  = document.getElementById('password-input');
            const eyeOn  = document.getElementById('eye-icon');
            const eyeOff = document.getElementById('eye-off-icon');

            if (input.type === 'password') {
                input.type = 'text';
                eyeOn.classList.add('hidden');
                eyeOff.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeOn.classList.remove('hidden');
                eyeOff.classList.add('hidden');
            }
        }

        // Loading state saat submit
        document.getElementById('login-form').addEventListener('submit', function() {
            const btn = document.getElementById('btn-submit');
            btn.disabled = true;
            btn.textContent = 'Memverifikasi...';
        });
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\k3-pltgu\resources\views/auth/login.blade.php ENDPATH**/ ?>