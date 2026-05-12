<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
class LoginController extends Controller
{
    public function show()
    {
        // Kalau sudah login, langsung redirect sesuai role
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // 1. Buat "kunci" unik berdasarkan kombinasi Email dan IP Address pengguna
        $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();

        // 2. Cek apakah pengguna sudah gagal login 5 kali berturut-turut
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            // Hitung sisa waktu pemblokiran
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = ceil($seconds / 60);

            $waktu = $seconds < 60 ? "{$seconds} detik" : "{$minutes} menit";

            return back()
                ->withErrors(['email' => "Terlalu banyak percobaan gagal. Silakan coba lagi dalam {$waktu}."])
                ->onlyInput('email');
        }

        // 3. Proses percobaan login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // 4a. Jika BERHASIL login, bersihkan catatan kegagalan sebelumnya
            RateLimiter::clear($throttleKey);

            $request->session()->regenerate();

            return $this->redirectByRole(Auth::user());
        }

        // 4b. Jika GAGAL login, tambahkan 1 catatan kegagalan (hukuman berlaku 60 detik)
        RateLimiter::hit($throttleKey, 60);

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda berhasil logout.');
    }

    private function redirectByRole($user)
    {
        if ($user->hasRole('admin_k3')) {
            return redirect()->intended('/admin');
        }

        if ($user->hasAnyRole(['dokter', 'perawat'])) {
            return redirect()->intended('/klinik');
        }

        // Role tidak dikenali → logout paksa
        Auth::logout();
        return redirect()->route('login')
            ->withErrors(['email' => 'Akun Anda tidak memiliki akses ke panel manapun. Hubungi Administrator.']);
    }
}
